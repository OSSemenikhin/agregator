import Chooser from '../plugins/chooser/';

export default class locationFilter {
  city = {
    storageMark: null,
    all: null,
    options: {},
    input: null,
    current: null,
    select: null,
    saved: null,
    start: null,
  };

  parent = null;

  open = {
    area: false,
    subway: false
  };

  buttons = {
    area: null,
    subway: null,
  };

  query = null;

  collapse = {
    area: null,
    subway: null,
  };

  constructor(
    parentId,
    areaButtonId,
    subwayButtonId,
    cityInputId,
    cityOptions,
    collapse,
    cityStorageMark = 'city',
    startCity = null,
  ) {
    this.queryData();

    this.parent = document.getElementById(parentId);
    this.parent.addEventListener('click', this.toggle.bind(this));

    this.buttons.area = areaButtonId;
    this.buttons.subway = subwayButtonId;
    this.city.input = document.getElementById(cityInputId);

    this.collapse.area = collapse.area;
    this.collapse.subway = collapse.subway;

    this.city.options = cityOptions;
    this.city.storageMark = cityStorageMark;
    this.city.start = startCity;
    this.city.saved = +localStorage.getItem(this.city.storageMark);

    this.initialize();
  }

  initialize = async () => {
    this.city.all = await this.getAllCities();
    this.setCityOptions(this.city.all);
    this.city.select = new Chooser(this.city.options);

    if (!this.query?.city) await this.getCurrentCity(this.city.all);
    else {
      const city = this.city.all.data.find(item => item.index == this.query.city);
      if (city) this.setCurrentCity(city.id, true);
      else await getCurrentCity(this.city.all);
    }
  }

  getBody = async (url) => {
    let resp = await fetch(url);
    resp = await resp.text();
    return resp;
  }

  addFilter = async (url) => {
    const body = await this.getBody(url);
    if (body) {
      this.parent.innerHTML = '';
      this.parent.innerHTML = body;
      return true;
    }
    return false;
  }

  addLocationFilters = async (city) => {
    const areas = await this.addFilter(`/location/${city}?${document.location.search}`);
    if (areas) {
      this.show();
    }
  }

  show = () => {
    for (let key in this.collapse) {
      if (this.open[key]) {
        document.getElementById(this.collapse[key]).classList.add('show');
        document.getElementById(this.buttons[key]).setAttribute('aria-expanded', true);
      }
    }
  }

  getCurrentCity = async (all) => {
    if (this.city.saved) this.setCurrentCity(this.city.saved, true);
    else if (this.city.start) {
      const city = all.find(city => city.name == this.city.start);
      if (city) this.setCurrentCity(city.id, true);
    } else {
      ymaps.ready(async function () {
        const city = ymaps.geolocation.city;
        const check = all.find(item => item.name == city);
        if (check) this.setCurrentCity(check.id, true);
      });
    }
  }

  setCurrentCity(current, option = false) {
    this.city.select.select(current, option);
  }

  onSelectCity = (id) => {
    localStorage.setItem(this.city.storageMark, id);
    this.city.current = id;
    this.city.input.value = id;
    this.addLocationFilters(id);
  }

  setCityOptions = (cities) => {
    cities.forEach(city => {
      this.city.options.data.push({
        value: city.name,
        index: city.id,
        attr: {
          'val': city.id
        }
      });
    });
    this.city.options.onSelect = (item) => {
      this.onSelectCity(item.index);
    }
  }

  getSubwayItems = () => {
    return document.querySelectorAll('[id^="subway_"]');
  };

  getAreaItems = () => {
    return document.querySelectorAll('[id^="area_"]');
  };

  toggle = (e) => {
    for (const key in this.buttons) {
      if (!e.target.id.includes(key)) continue;
      this.open[key] = !this.open[key];
    }
  };

  getAllCities = async () => {
    let resp = await fetch('/cities');
    resp = await resp.json();
    return resp;
  }

  queryData = () => {
    let params = (new URL(document.location)).searchParams;
    const iterator = params.entries();
    let param = iterator.next();
    do {
      if (param && param.value) {
        this.query[param.value[0]] = param.value[1];
        param = iterator.next();
      }
    } while (!param.done)
  }
}
