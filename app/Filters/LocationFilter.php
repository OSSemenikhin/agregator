<?php

namespace App\Filters;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\CookieController;
use App\Constants\CookieConstants;
use App\Filters\BaseFilter;
use App\Models\Area;

class LocationFilter extends BaseFilter
{
    private $subway;
    private $area;

    /**
     * @param $items = [
     *   [
     *      'name' => string,                      -- query option name
     *      'label' => string                      -- label for otion
     *      'attributes' => array ['key'=>'value'] -- any html attributes
     *      '*relations*' => array                 -- relations items
     *   ]
     * ];
     */

    public function __construct(
        string $name,
        string $label,
        string $field,
        array $attributes = [],
        string $related = null
    ) {
        parent::__construct($name, $label, $field, $attributes, $related);
        $this->subway = $this->request['subway'] ?? false;
        $this->area = $this->request['area'] ?? false;
    }

    public function getItems(int $city_id): Collection
    {
        if (!$city_id) return new  Collection([]);
        return Area::getByCityIdWithSubways($city_id)->get();
    }

    // Отрисовка фильтра
    public function render(int|null $city_id = null): View|Factory|Application
    {
        if (!$city_id) $city_id = $request['city'] ?? CookieController::getCookie(CookieConstants::LOCATION) ?? null;
        $items = $this->getItems(+$city_id);
        return view('filters.' . $this->getName(), ['filter' => $this, 'request' => $this->request, 'city_id' => $city_id, 'items'=> $items]);
    }

    public function apply(Builder $query): Builder
    {
        if ($this->subway) $query = $query->whereHas('subways', function (Builder $query) {
            return $query->whereIn('id', $this->subway);
        });
        else if ($this->area) $query = $query->whereIn('area_id', $this->area);
        return $query;
    }
}
