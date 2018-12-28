function AccessoryManagement(opts) {
	var options = opts;

	var elements = {
		activeId: $('#activeId'),
		accessoryList: $('#accessoriesTable'),

		addUnlimited: $('#chkUnlimitedAdd'),
		addQuantity: $('#addQuantity'),
		
		editName: $('#editName'),
		editUnlimited: $('#chkUnlimitedEdit'),
		editQuantity: $('#editQuantity'),
		
		editDialog: $('#editDialog'),
		deleteDialog: $('#deleteDialog'),
		accessoryResourcesDialog: $('#accessoryResourcesDialog'),

		addForm: $('#addForm'),
		form: $('#editForm'),
		deleteForm: $('#deleteForm'),
		accessoryResourcesForm: $('#accessoryResourcesForm')
	};

	var accessories = new Object();

	AccessoryManagement.prototype.init = function() {

		elements.accessoryList.delegate('a.update', 'click', function(e) {
			setActiveId($(this));
			e.preventDefault();
		});

		elements.accessoryList.delegate('.edit', 'click', function() {
			editAccessory();
		});

		elements.accessoryList.delegate('.delete', 'click', function() {
			deleteAccessory();
		});

		elements.accessoryList.delegate('.resources', 'click', function() {
			showAccessoryResources();
		});

		$(".save").click(function() {
			$(this).closest('form').submit();
		});

		$(".cancel").click(function() {
			$(this).closest('.dialog').dialog("close");
		});


		elements.accessoryResourcesDialog.delegate('.resourceCheckbox', 'click', function() {
			handleAccessoryResourceClick($(this));
		});

		ConfigureAsyncForm(elements.addForm, getSubmitCallback(options.actions.add));
		ConfigureAsyncForm(elements.deleteForm, getSubmitCallback(options.actions.deleteAccessory));
		ConfigureAsyncForm(elements.form, getSubmitCallback(options.actions.edit));
		ConfigureAsyncForm(elements.accessoryResourcesForm, defaultSubmitCallback);

		WireUpUnlimited(elements.addUnlimited, elements.addQuantity);
		WireUpUnlimited(elements.editUnlimited, elements.editQuantity);
	};

	var getSubmitCallback = function(action) {
		return function() {
			return options.submitUrl + "?aid=" + getActiveId() + "&action=" + action;
		};
	};

	var defaultSubmitCallback = function (form)
	{
		return options.submitUrl + "?aid=" + getActiveId() + "&action=" + form.attr('ajaxAction');
	};

	function setActiveId(activeElement) {
		var id = activeElement.closest('tr').attr('data-accessory-id');
		elements.activeId.val(id);
	}

	function getActiveId() {
		return elements.activeId.val();
	}

	var editAccessory = function() {
		var accessory = getActiveAccessory();
		elements.editName.val(accessory.name);
		// For use with stock management, this field will now be interpreted as a relative change of stock level.
		// For example, "10" or "-10". So, no need anymore to initialize it with the current stock level.
		// changed by matthias@ansorgs.de 2018-12-29
		// elements.editQuantity.val(accessory.quantity);

		if (accessory.quantity == '')
		{
			elements.editUnlimited.prop('checked', true);
		}
		else
		{
			elements.editUnlimited.prop('checked', false);
		}

		elements.editUnlimited.trigger('change');
		elements.editDialog.modal('show');
	};

	function handleAccessoryResourceClick(checkbox)
	{
		var quantities = checkbox.closest('div[resource-id]').find('.quantities');

		if (checkbox.is(':checked'))
		{
			quantities.removeClass('no-show');
		}
		else
		{
			quantities.addClass('no-show');
		}
	}

	var showAccessoryResources = function()
	{
		var accessory = getActiveAccessory();

		$.get(opts.submitUrl + '?dr=accessoryResources&aid=' + accessory.id, function(data)
		{
			elements.accessoryResourcesDialog.find(':checkbox').prop('checked', false);
			elements.accessoryResourcesDialog.find('.hidden').hide();

			$.each(data, function(idx, resource){
				var div = elements.accessoryResourcesDialog.find('[resource-id="' + resource.ResourceId + '"]');
				var checkbox = div.find(':checkbox');
				checkbox.prop('checked', true);
				handleAccessoryResourceClick(checkbox);

				div.find('[data-type="min-quantity"]').val(resource.MinQuantity);
				div.find('[data-type="max-quantity"]').val(resource.MaxQuantity);
			});
			elements.accessoryResourcesDialog.find('.resourcesDialogLabel').val(accessory.name + ' (' + accessory.quantity + ')');
			elements.accessoryResourcesDialog.modal('show');
		});
	};

	var deleteAccessory = function() {
		elements.deleteDialog.modal('show');
	};

	var getActiveAccessory = function ()
	{
		return accessories[getActiveId()];
	};

	var WireUpUnlimited = function(checkbox, quantity)
	{
		checkbox.change(function(){
			if (checkbox.is(":checked"))
			{
				quantity.val('');
				quantity.attr('disabled', 'disabled');
			}
			else
			{
				quantity.removeAttr('disabled');
			}
		});
	};

	AccessoryManagement.prototype.addAccessory = function(id, name, quantity)
	{
		accessories[id] = {id: id, name: name, quantity: quantity};
	};
}
