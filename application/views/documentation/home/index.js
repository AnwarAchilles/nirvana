
/**
 * Define a custom component extends the "Nirvana" class.
 * This component is used within the NV framework.
 *
 * @extends Nirvana
 */
NV.component(
  class Home extends Nirvana {

    /**
     * Constructor for the component.
     * You can perform additional setup and logic here.
     */
    constructor() {
      super();

      // Add your initialization code here
    }


    toast = NV.load("BootstrapToast").instance({ core:this });
    startToast() {
      this.toast.open({
        icon: "circle-check",
        title: "Success "+this.constructor.component,
        message: "Success - Something to toast",
        background: "success",
      });
    }
  }
);