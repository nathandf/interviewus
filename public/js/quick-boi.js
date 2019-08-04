$( function () {
	var propertyBuilder = {
		iteration: 0,
		iterate: function () {
			this.iteration++;
		},
		newPropertyRow: function () {
			this.iterate();
			var property_columns, property_name, data_type, value_length, is_null, is_primary, table_open, table_close;

			table_open = "<tr class=\"table-row\">";
			hidden_properties_tracker = "<input type='hidden' name='property_indicies[]' value='" + this.iteration + "'>";
			value_length_options = "<option selected=\"selected\" hidden=\"hidden\"value=\"\">Choose</option><option value=\"BIGINT\">BIGINT</option><option value=\"TINYINT\">TINYINT</option><option value=\"VARCHAR\">VARCHAR</option><option value=\"MEDIUMTEXT\">MEDIUMTEXT</option>";
			property_name = "<td style=\"text-align: center;\"><input name=\"property_row_" + this.iteration + "[property_name]\" data-iteration=\"" + this.iteration + "\" id=\"prop-" + this.iteration + "\" class=\"inp inp-full --model-prop\" required=\"required\"></td>";
			data_type = "<td style=\"text-align: center;\"><select name=\"property_row_" + this.iteration + "[data_type]\" class=\"inp inp-full cursor-pt\" required=\"required\">" + value_length_options + "</select></td>";
			value_length = "<td style=\"text-align: center;\"><input name=\"property_row_" + this.iteration + "[value_length]\" class=\"inp inp-full\"></td>";
			is_null = "<td style=\"text-align: center;\"><input type=\"checkbox\" class=\"checkbox\" name=\"property_row_" + this.iteration + "[is_null]\"></td>";
			is_primary = "<td style=\"text-align: center;\"><input type=\"checkbox\" class=\"checkbox\" name=\"property_row_" + this.iteration + "[is_primary]\"></td>";
			auto_increment = "<td style=\"text-align: center;\"><input type=\"checkbox\" class=\"checkbox\" name=\"property_row_" + this.iteration + "[auto_increment]\"></td>";
			trash = "<td style=\"text-align: center;\"><p class=\"tc-red text-xlrg-heavy cursor-pt table-row-trash\">x</p></td>";
			table_close = "</tr>"

			return property_columns = table_open + hidden_properties_tracker + property_name + data_type + value_length + is_null + is_primary + auto_increment + trash;
		}
	};

	$( ".--id-string" ).on( "keyup", function() {
		$( this ).val( $( this ).val().toLowerCase().split( " " ).join( "-" ).split( "--" ).join( "-" ) );
    });

	$( "#add-property" ).on( "click", function () {
		$( "#property-table" ).append( propertyBuilder.newPropertyRow() );
		$( ".--model-prop" ).on( "keyup", function() {
			$( "#prop-" + this.dataset.iteration ).val( $( this ).val().toLowerCase().split( " " ).join( "_" ).split( "__" ).join( "_" ) );
	    });
	} );

	$( "#property-table" ).on( "click", ".table-row-trash", function () {
		// confirmation = confirm( "Are you sure you want to delete this?" );
        $( this ).parent().parent().remove();
	} );
} );
