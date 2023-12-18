NV.component(
  class ElementObserver {

    inSelect = document.querySelector("body");

    constructor({}) {}

    execute(element, callback) {
      let targetElement = element;
      const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            callback();
          }
        });
      });
      observer.observe(targetElement);
    }

  }
);