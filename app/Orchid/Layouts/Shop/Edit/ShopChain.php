<?php

namespace App\Orchid\Layouts\Shop\Edit;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use App\Orchid\Fields\Title;
use Orchid\Screen\Actions\Button;

class ShopChain extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    private function getRow($chains, $shop)
    {
        $row = [
            Title::make('Принадлежит сети'),
            Group::make([
                Select::make('chains')->options($chains)->empty($chains[$shop->chain_id] ?? '', $shop->chain_id ?? ''),
                Button::make('Сохранить')->method('save-chain'),
            ]),
        ];

        if ($shop->id) {
            $row[] = Button::make('Сохранить')->method('save-cahains')->class('btn btn-success m-auto')->right();
        }

        return $row;
    }

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        $shop = $this->query->get('shop');
        $chains = \App\Models\Chain::all()->pluck('name', 'id');

        return $this->getRow($chains, $shop);
    }
}
