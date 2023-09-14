<div id="filter_location">
    <p class="filter__aside-label mt-3 mb-15">Район</p>

    @include('filters.location-item', [
        'items' => $items,
        'name' => 'area',
        'label' => 'Район',
        'city_id' => $city_id,
        'request' => $request,
        'filterID' => $filter->getAttribute('area_id'),
    ])

    <p class="filter__aside-label mt-3 mb-15">Метро</p>

    @include('filters.location-item', [
        'items' => $items->pluck('subways')->flatten()->all(),
        'name' => 'subway',
        'label' => 'Метро',
        'city_id' => $city_id,
        'request' => $request,
        'groupField' => 'area_id',
        'filterID' => $filter->getAttribute('subway_id'),
        'groups' => [[
            'name' => 'area',
            'type' => 'target',
            'value_prefix' => 'area_',
        ]]
    ])
</div>

