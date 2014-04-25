function printInvoice() {
	window.print();
	return true;
}

jQuery(function($) {
  // Depending on the checkout, buttonAction and button must be changed
  var buttonAction = function() {
	  payment.save();
  };
  // This is the button that saves the payment method, or completes the checkout if it has only one step.
  var button = $(":button[onclick='payment.save()']");
  
  var conektaSuccessResponseHandler = function(response) {
	  var $form = $("#payment_form_card");
	  var token_id = response.id;
	  $form.find('#card_cc_tokenid').val(token_id);
	  $form.find('.card-errors').text("");
	  $.ajax({
		  type: "POST",
		  url: cardUrl,
		  dataType: "text",
		  data: "token_id=" + token_id,
		  async: false,
		  success: function (data) {
			  var $form = $("#payment_form_card");
			  data = $.parseJSON(data);
			  if (data.error) {
				  $form.find('.card-errors').text(data.error);
			  } else if (data.status == "paid") {
				  buttonAction();
			  } else {
				  $form.find('.card-errors').text("Método de pago no disponible, intente más tarde.");
			  }
		  },
		  error: function (textStatus, errorThrown) {
			  var $form = $("#payment_form_card");
			  $form.find('.card-errors').text("Método de pago no disponible, intente más tarde.");
		  }
	  });
  };
  var conektaErrorResponseHandler = function(response) {
	  var $form = $("#payment_form_card");
	  $form.find('.card-errors').text(response.message);
  };

  var invoice = function(data) {
	  var result = "";
	  if (data.barcode) {
		  result = "<div>"
				  + "<form id='co-invoice-form' action=''>"
				  + "	<fieldset>"
				  + "		<ul class='form-list'>"
				  + "	<li class='ckta ficha-oxxo' id='checkout-invoice-load'>													"
				  + "		<div class='col-1'>"
				  + "			<h3>Ficha de Pago para OXXO</h3>"
				  + "			<p>Utiliza esta ficha para realizar el pago en la tienda OXXO más cercana . <span class='fade'>Si intentas realizar el pago después de esta necesitarás un nuevo código de barras</span>.</p>"
				  + "			<div class='ficha barcode'>"
				  + "				<h3>Ficha de Pago para OXXO</h3>"
				  + "				<img src='"+ data.barcode +"' width='450' height='75' alt='Código de Barras para OXXO'/>'"
				  + "				<p><span>Monto: $ "+ (data.amount/100.0).toFixed(2) +"</span><span>" + data.referencia + "</span></p>"
				  + "			</div>"
				  + "		</div>"
				  + "		<div class='col-2'>"
				  + "			<h3></h3>"
				  + "			<h4>Acciones</h4>"
				  + "			<p>"
				  + "				<a href='#' class='button' id='print' onclick='printInvoice();'>Imprimir ficha</a>"
				  + "			</p>"
				  + "		</div>"
				  + "	</li>"
				  + "		</ul>		"
				  + "	</fieldset>"
				  + "</form>"
				  + ""
				  + "</div>";
	  } else {
		  result = "<div id='dialog-modal' title='Basic modal dialog'>"
				  + "<form id='co-invoice-form' action=''>"
				  + "	<fieldset>"
				  + "		<ul class='form-list'>"
				  + "	<li class='ckta ficha-banco' id='checkout-invoice-load'>													"
				  + "		<div class='col-1'>"
				  + "			<h3>Ficha de Pago para Bancos</h3>"
				  + "			<p>Utiliza esta ficha para realizar el pago en <strong>sucursales Banorte</strong>. <a href='http://bit.ly/SucursalesBanorte' target='_blank'>Haz clic aquí</a><span class='fade'> para encontrar la sucursal Banorte más cercana.</span>"
				  + "			</p>"
				  + "			<div class='ficha reference'>"
				  + "				<h3>Ficha de Pago para Bancos</h3>"
				  + "				<dl>"
				  + "					<div class='dl-box'>"
				  + "						<dt>Banco</dt>"
				  + "						<dd>"+ data.bank +"</dd>"
				  + "					</div>"
				  + "					<div class='dl-box'>"
				  + "						<dt>Servicio</dt>"
				  + "						<dd>"+ data.nombre_sevicio +"</dd>"
				  + "					</div>"
				  + "					<div class='dl-box'>"
				  + "						<dt>No. de Servicio</dt>"
				  + "						<dd>"+ data.numero_servicio +"</dd>"
				  + "					</div>"
				  + "					<div class='dl-box'>"
				  + "						<dt>No. de Referencia</dt>"
				  + "						<dd>"+ data.referencia +"</dd>"
				  + "					</div>"
				  + "				</dl>"
				  + "				<p class='ammount'><strong>$ "+(data.amount/100.0).toFixed(2)+"</strong></p>"
				  + "			</div>"
				  + "		</div>"
				  + "		<div class='col-2'>"
				  + "			<h3></h3>"
				  + "			<h4>Acciones</h4>"
				  + "			<p>"
				  + "				<a href='#' class='button' id='print' onclick='printInvoice();'>Imprimir ficha</a>"
				  + "			</p>"
				  + "		</div>"
				  + "	</li>"
				  + "		</ul>		"
				  + "	</fieldset>"
				  + "</form>"
				  + ""
				  + "</div>";
	  }
	  $(result).dialog({
			  resizable: false,
			  modal: true,
			  width:'auto'
	  }).bind('dialogclose', function(event) {
		   buttonAction();
	   });
  };

  button.unbind('click');
  button.attr('onclick','');
  button.click(function() {
	  if ($("#p_method_card").prop('checked')) {
		  var $form = $("#payment_form_card");
		  $form.find('#card_cc_nombre').val($("#card_cc_owner").val());
		  $form.find('#card_cc_numero').val($("#card_cc_number").val());
		  $form.find('#card_cc_mes').val($("#card_expiration").val());
		  $form.find('#card_cc_anio').val($("#card_expiration_yr").val());
		  Conekta.token.create($form, conektaSuccessResponseHandler, conektaErrorResponseHandler);
	  } else if ($("#p_method_oxxo").prop('checked')) {
		  $.ajax({
			  type: "POST",
			  url: oxxoUrl,
			  dataType: "text",
			  data: "",
			  async: false,
			  success: function (data) {
				  var $form = $("#payment_form_oxxo");
				  data = $.parseJSON(data);
				  if (data.error) {
					  $form.find('.oxxo-errors').text(data.error);
				  } else if (data.barcode) {
					  $form.find('.oxxo-errors').text("");
					  $form.find('#codigo_barras').val(data.barcode);
					  $form.find('#referencia').val(data.referencia);
					  $form.find('#codigo').val(data.codigo);
					  invoice(data);
				  } else {
					  $form.find('.oxxo-errors').text("Método de pago no disponible, intente más tarde.");
				  }
			  },
			  error: function (textStatus, errorThrown) {
				  var $form = $("#payment_form_oxxo");
				  $form.find('.oxxo-errors').text("Método de pago no disponible, intente más tarde.");
			  }
		  });
    } else if ($("#p_method_bank").prop('checked')) {
		  $.ajax({
			  type: "POST",
			  url: bankUrl,
			  dataType: "text",
			  data: "",
			  async: false,
			  success: function (data) {
				  var $form = $("#payment_form_bank");
				  data = $.parseJSON(data);
				  if (data.error) {
					  $form.find('.bank-errors').text(data.error);
				  } else if (data.numero_servicio) {
					  $form.find('.bank-errors').text("");
					  $form.find('#numero_servicio').val(data.numero_servicio);
					  $form.find('#nombre_servicio').val(data.nombre_servicio);
					  $form.find('#bank').val(data.bank);
					  $form.find('#referencia').val(data.referencia);
					  invoice(data);
				  } else {
					  $form.find('.bank-errors').text("Método de pago no disponible, intente más tarde.");
				  }
			  },
			  error: function (textStatus, errorThrown) {
				  var $form = $("#payment_form_bank");
				  $form.find('.bank-errors').text("Método de pago no disponible, intente más tarde.");
			  }
		  });
	  } else {
		  buttonAction();
	  }
	  return false;
  });
});
