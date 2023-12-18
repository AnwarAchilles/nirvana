NV.component(
  class DataCollection {

    lastWord = '';

    constructor( core ) {}

    object( data ) {
      return new Map(data.map(data => [data.id, data]));
    }

    objectID(data, selectedId) {
      const resultMap = new Map();
      for (let i = 0; i < data.length; i++) {
          const item = data[i];
          resultMap.set(item[selectedId], item);
      }
      return resultMap;
    }
  }
)