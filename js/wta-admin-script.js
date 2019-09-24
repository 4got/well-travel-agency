(() => {
  window.crElem = (type, parent, innerHTML = '', attrObj = {}) => {
    let elt = document.createElement(type);
    for (let attr in attrObj) elt.setAttribute(attr, attrObj[attr]);
    elt.innerHTML = innerHTML;
    parent.appendChild(elt);
    return elt;
  };

  const pressed = [];
  let lastID = 0;
  window.invokeOnce = (callback, timeout) => {
    const localID = lastID++;
    pressed.push(localID);

    return setTimeout(() => {
      const localPressed = [...pressed];
      const go = localPressed.length === 1 && localPressed[0] === localID;
      pressed.splice(pressed.indexOf(localID), 1);
      return go ? callback() : false;
    }, timeout);
  };

  wtaSerialize = mixed_val => {
    switch (typeof mixed_val) {
      case 'number':
        if (isNaN(mixed_val) || !isFinite(mixed_val)) {
          return false;
        } else {
          return (
            (Math.floor(mixed_val) == mixed_val ? 'i' : 'd') +
            ':' +
            mixed_val +
            ';'
          );
        }
      case 'string':
        return 's:' + mixed_val.length + ':"' + mixed_val + '";';
      case 'boolean':
        return 'b:' + (mixed_val ? '1' : '0') + ';';
      case 'object':
        if (mixed_val == null) {
          return 'N;';
        } else if (mixed_val instanceof Array) {
          var idxobj = { idx: -1 };
          var map = [];
          for (var i = 0; i < mixed_val.length; i++) {
            idxobj.idx++;
            var ser = wtaSerialize(mixed_val[i]);

            if (ser) {
              map.push(wtaSerialize(idxobj.idx) + ser);
            }
          }

          return 'a:' + mixed_val.length + ':{' + map.join('') + '}';
        } else {
          var class_name = get_class(mixed_val);

          if (class_name == undefined) {
            return false;
          }

          var props = new Array();
          for (var prop in mixed_val) {
            var ser = wtaSerialize(mixed_val[prop]);

            if (ser) {
              props.push(wtaSerialize(prop) + ser);
            }
          }
          return (
            'O:' +
            class_name.length +
            ':"' +
            class_name +
            '":' +
            props.length +
            ':{' +
            props.join('') +
            '}'
          );
        }
      case 'undefined':
        return 'N;';
    }

    return false;
  };
  window.wtaSerialize = wtaSerialize;
})();
