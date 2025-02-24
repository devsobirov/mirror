<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientSaveRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index()
    {
        $items = Client::query()
            ->search(request('search_client'))
            ->withSum('debts as debt_sum', 'amount')
            ->withAvg(['debts as rating' => fn($q) => $q->whereNotNull('rating')], 'rating')
            ->withSum('payments as paid_sum', 'amount')
            ->paginate();

        $items->map(fn($item) => $item->debt = $item->debt_sum - $item->paid_sum);
        return view('finance.clients', compact('items'));
    }

    public function show(Client $client)
    {
        $debts = $client->debts()->orderBy('payment_date', 'desc')->with('payments')->get();
        $debts->map(fn($debt) => $debt->debt = $debt->debt_sum - $debt->paid_sum);
        return view('finance.client', compact('client', 'debts'));
    }

    public function save(ClientSaveRequest $request, Client $client)
    {
        $client->fill($request->validated());
        $client->save();
        return redirect()->back()->with('success', 'Muvaffaqiyatli saqlandi');
    }
}
