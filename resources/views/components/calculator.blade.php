<div x-data="{ open: false, x: null, y: null, quantity: 1 }" x-show="open" @open.window="open = true; x = $event.detail.x; y = $event.detail.y; quantity = $event.detail.quantity;">
    <button type="button" @click="open = true" class="btn btn-icon btn-sm btn-ghost-secondary mx-1">
        <x-svg.calc class="icon"></x-svg.calc>
    </button>

    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Калькулятор для <span x-text="$parent.item.name"></span></h5>
                    <button type="button" class="close" @click="open = false" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="width">Ширина (x)</label>
                        <input type="number" id="width" x-model="x" class="form-control" placeholder="Введите ширину" required>
                    </div>
                    <div class="form-group">
                        <label for="height">Высота (y)</label>
                        <input type="number" id="height" x-model="y" class="form-control" placeholder="Введите высоту" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Количество</label>
                        <input type="number" id="quantity" x-model="quantity" class="form-control" placeholder="Введите количество" min="1" value="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="open = false">Отмена</button>
                    <button type="button" class="btn btn-primary" 
                            :disabled="!x || !y || quantity < 1" 
                            @click="$dispatch('apply', { x: x, y: y, quantity: quantity }); open = false;">Применить</button>
                </div>
            </div>
        </div>
    </div>
</div> 