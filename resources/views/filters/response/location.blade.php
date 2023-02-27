@php
    if (isset($params)) $city_id = $params ?? null;
    else if (isset($request['city'])) $city_id = $request['city'] ?? null;
    $items = $filter->getItems(+$city_id);
@endphp
@include('filters.location-filter', [
    'items' => $items,
    'name' => 'area',
    'label' => 'Район',
    'city_id' => $city_id,
    'request' => $request,
    ])
@include('filters.location-filter', [
    'items' => $items->pluck('subways')->flatten()->all(),
    'name' => 'subway',
    'label' => 'Метро',
    'city_id' => $city_id,
    'request' => $request,
    'groupField' => 'area_id',
    'groups' => [
        [
            'name' => 'area',
            'type' => 'target',
            'value_prefix' => 'area_',
        ]
    ]
])
