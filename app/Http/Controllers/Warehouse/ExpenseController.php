<?php

namespace App\Http\Controllers\Warehouse;

use App\Exports\ExpenseReportExport;
use App\Http\Controllers\Warehouse\BaseWarehouseController as Controller;
use App\Http\Requests\ExpenseUpdateRequest;
use App\Models\Client;
use App\Models\Image;
use App\Models\Warehouse\Balance;
use App\Models\Warehouse\Category;
use App\Models\Warehouse\Good;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    public function index()
    {
        $items = Balance::query()
            ->byType($this->action_type)
            ->byWarehouse(request('warehouse_id') ?: $this->allowed_warehouse_ids)
            ->filterByGood(request()->only('search', 'category_id'))
            ->searchByClient(request('search_client'))
            ->from(request('from'))
            ->to(request('to'))
            ->with(
                ['good' => fn($query) => $query->select('id', 'name', 'category_id', 'image')->with('category')],
                'clientImages',
                'user:id,name',
                'warehouse:id,name',
            )
            ->orderBy('id', 'desc')
            ->paginate(30)
            ->withQueryString();

        $categories = Category::select('id', 'title')->get();
        $warehouses = $this->allowed_warehouses;
        return view('sklad.expense', compact('items', 'categories', 'warehouses'));
    }

    public function expense(Request $request)
    {
        $extend = [
            'client_id' => $this->getClientId($request->only('client_name', 'phone', 'phone2', 'address'))
        ];
        $balance = $this->saveBalance($request, $extend);
        $this->updateStat($request->good_id, $request->warehouse_id, $request->amount);
        if ($balance && $request->hasFile('client_images')) {
            $this->saveClientImages($request->file('client_images'), $balance->id);
        }
        return redirect()->back()->with('success', 'Muvaffqiyatli saqlandi');
    }

    public function edit(Balance $balance)
    {
        if (!auth()->user()->isAdmin() || $balance->type !== $this->action_type) {
            abort(403, 'Huquqlar yetarli emas');
        }

        $warehouses = $this->allowed_warehouses;
        return view('sklad.expense-edit', compact('balance','warehouses'));
    }

    public function update(ExpenseUpdateRequest $request,Balance $balance)
    {
        if (!auth()->user()->isAdmin() || $balance->type !== $this->action_type) {
            abort(403, 'Huquqlar yetarli emas');
        }
        $data = $request->validated();
        $data['client_id'] = $this->getClientId(
            $request->only('client_name', 'phone', 'phone2', 'address'),
            $balance->client_id
        );

        $originalAmount = $balance->amount;
        $originalWarehouse = $balance->warehouse_id;
        $diff = $data['amount'] - $originalAmount;
        if ($data['created_at'] === $balance->created_at?->format('Y-m-d')) {
            $data['created_at'] = $balance->created_at->format('Y-m-d');
        } else {
            $data['created_at'] = $data['created_at'] . ' '. date('H:i:s');
        }

        $balance->fill($data);
        $balance->save();

        if ($originalWarehouse != $data['warehouse_id']) {
            $this->updateStat($balance->good_id, $data['warehouse_id'], $balance->amount);
            $this->action_type = Balance::TYPE_CANCEL_EXPENSE;
            $this->updateStat($balance->good_id, $originalWarehouse, $originalAmount);
        } elseif ($diff) {
            $this->updateStat($balance->good_id, $originalWarehouse, $diff);
        }

        if ($request->hasFile('client_images')) {
            $this->saveClientImages($request->file('client_images'), $balance->id);
        }

        return redirect()->back()->with('success', 'Muvaffqiyatli yangilandi');
    }

    public function delete(Balance $balance)
    {
        $this->action_type = Balance::TYPE_CANCEL_EXPENSE;
        $this->updateStat($balance->good_id, $balance->warehouse_id, $balance->amount);
        $balance->delete();

        return redirect()->route('sklad.expenses.index')->with('success', 'Muvaffaqiyatli o\'chirildi');
    }

    public function export(Request $request, ?Good $good)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Huquqlar yetarli emas');
        }

        $filename = \Str::slug($request->input('filename')) . '.xlsx';
        $items = Balance::query()
            ->byType($this->action_type)
            ->byGood($good?->id)
            ->byWarehouse($request->export_warehouse_id)
            ->filterByGood(['category_id' => $request->export_category_id])
            ->from($request->export_from)
            ->to($request->export_to)
            ->with(
                ['good' => fn($query) => $query->select('id', 'name', 'category_id')->with('category')],
                'client:id,name,phone,phone2',
                'user:id,name',
                'warehouse:id,name',
            )
            ->orderBy('id', 'desc')
            ->get();

        return Excel::download(new ExpenseReportExport($items), $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    protected function setActionType(): void
    {
        $this->action_type = Balance::TYPE_EXPENSE;
    }

    private function getClientId(array $data, ?int $client_id = null): ?int
    {
        if ($name = $data['client_name'] ?? null) {
            $data = [
                'name' => $name,
                'phone' => $this->clearPhone($data['phone'] ?? null),
                'phone2' => $this->clearPhone($data['phone2'] ?? null),
                'address' => $data['address'] ?? null,
            ];

            $client = $client_id ? (Client::where('id', $client_id)->first() ?? new Client()) : new Client();
            $client->fill($data);
            $client->save();
            return $client->id;
        }
        return null;
    }

    private function saveClientImages(array $images, $expenseId): void
    {
        if ($images) {
            // Массив для хранения путей к файлам
            foreach ($images as $image) {
                $path = 'assets/images/clients/'. date('Y-m');
                $filename = \Str::random(10) . '.' . $image->getClientOriginalExtension();
                // Сохраняем каждый файл в директорию 'public/images' с уникальным именем
                /** @var UploadedFile $image */
                $image->move($path, $filename);
                //$path = $image->store($path, 'public');

                // Создаем запись в базе данных для каждого изображения
                Image::create([
                    'filename' => $path . '/' . $filename, // Путь к файлу, сохраненный в хранилище
                    'rel_type' => Balance::class, // Связанный тип (если это баланс, то используем Balance::class)
                    'rel_id' => $expenseId, // ID расхода, к которому привязываем изображение
                ]);
            }
        }
    }

    private function clearPhone($phone): ?string
    {
        if ($phone) {
            $phone = preg_replace('/\D/', '', $phone);
            return $phone ?: null;
        }

        return null;
    }
}
