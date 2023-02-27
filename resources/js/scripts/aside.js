import LocationFilter from '../components/filters/LocationFilter';
import optionsCity from '../components/filters/cityFilterOptions';

document.addEventListener('DOMContentLoaded', async () => {
  const collapse = {
    area: 'collapse_filter_area_inner',
    subway: 'collapse_filter_subway_inner'
  }

  new LocationFilter(
    'filter_location',
    'filter_area_button',
    'filter_subway_button',
    'filter_city',
    optionsCity,
    collapse,
    'city',
  );
});
