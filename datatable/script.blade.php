
	<script type="text/javascript">
	    $(function() {
	        window.table_id = '#' + '{{ $datatable_id }}';

	        window.table = $(table_id).DataTable({
	        	responsive: true,

	        	@if (isset($columnSort))
	        	order: [[ {{ $columnSort }}, "{{ $sorting }}" ]],
	        	@endif
	        	
	            @if (!$search && App::getLocale() == 'es')
	            language: {
	                url: "https://cdn.datatables.net/plug-ins/1.10.10/i18n/Spanish.json"
	            }
	            @endif
	        });

			@if ($search)
		        window.table_h = $(table_id + ' thead th');
		        window.table_f = $(table_id + ' tfoot th');

			    table_h.each(function () {
			        var title = $(this).text();
			        $(this).html( '<input type="text" placeholder="' + title + '" />' );
			    });

			    table_f.each(function () {
			        var title = $(this).text();
			        $(this).html( '<input type="text" placeholder="' + title + '" />' );
			    });

			    table.columns().every(function () {
			        var that = this;
			 
			        $('input', this.footer()).on('keyup change', function () {
			            if (that.search() !== this.value)
			            {
			                that
			                    .search(this.value)
			                    .draw();
			            }
			        });
			    });
		    @endif
	    });
	</script>