var Ficha = Class.create(Checkout, {
	initialize: function($super,accordion, urls){
		$super(accordion, urls);
		//New Code Addded
		this.steps = ['login', 'billing', 'shipping', 'shipping_method', 'payment','ficha', 'review'];
	},
	setMethod: function(){
	    if ($('login:guest') && $('login:guest').checked) {
	        this.method = 'guest';
	        var request = new Ajax.Request(
	            this.saveMethodUrl,
	            {method: 'post', onFailure: this.ajaxFailure.bind(this), parameters: {method:'guest'}}
	        );
	        Element.hide('register-customer-password');
	        this.gotoSection('billing'); //New Code Here
	    }
	    else if($('login:register') && ($('login:register').checked || $('login:register').type == 'hidden')) {
	        this.method = 'register';
	        var request = new Ajax.Request(
	            this.saveMethodUrl,
	            {method: 'post', onFailure: this.ajaxFailure.bind(this), parameters: {method:'register'}}
	        );
	        Element.show('register-customer-password');
	        this.gotoSection('billing'); //New Code Here
	    }
	    else{
	        alert(Translator.translate('Please choose to register or to checkout as a guest'));
	        return false;
	    }
	}
});

var FichaMethod = Class.create();
FichaMethod.prototype = {
    initialize: function(saveUrl){
        this.saveUrl = saveUrl;
        this.onSave = this.nextStep.bindAsEventListener(this);
        this.onComplete = this.resetLoadWaiting.bindAsEventListener(this);
    },
    save: function(){

        if (checkout.loadWaiting!=false) return;
            checkout.setLoadWaiting('ficha');
            var request = new Ajax.Request(
                this.saveUrl,
                {
                    method:'post',
                    onComplete: this.onComplete,
                    onSuccess: this.onSave,
                    onFailure: checkout.ajaxFailure.bind(checkout)
                }
            );
    },

    resetLoadWaiting: function(transport){
        checkout.setLoadWaiting(false);
    },

    nextStep: function(transport){
        if (transport && transport.responseText){
            try{
                response = eval('(' + transport.responseText + ')');
            }
            catch (e) {
                response = {};
            }
        }

        if (response.error) {
            alert(response.message);
            return false;
        }

        if (response.update_section) {
            $('checkout-'+response.update_section.name+'-load').update(response.update_section.html);
        }


        if (response.goto_section) {
            checkout.gotoSection(response.goto_section);
            checkout.reloadProgressBlock();
            return;
        }

        checkout.setReview();
    }
}
