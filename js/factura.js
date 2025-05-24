$(document).ready(function() {
    // Añadir nueva fila
    $('#addRows').on('click', function() {
        var isValid = true;
        var $lastRow = $('#invoiceItem tr:last');
        
        // Verifica si el último campo de selección de curso tiene un valor seleccionado
        var $lastSelect = $lastRow.find('select[name="productName[]"]');
        if ($lastSelect.val() === '') {
            alert('Por favor, selecciona un curso en la fila actual antes de añadir una nueva fila.');
            isValid = false;
        }

        if (isValid) {
            var newRow = `<tr>
                <td><input class="itemRow" type="checkbox"></td>
                <td>
                    <select name="productName[]" class="form-control">
                        <option value="" disabled selected>Seleccionar curso</option>
                        <option value="Estetica Corporal" data-price="50000">Estetica Corporal</option>
                        <option value="Estetica Facial" data-price="60000">Estetica Facial</option>
                        <option value="Masaje reductivo" data-price="70000">Masaje reductivo</option>
                        <option value="Drenaje Linfatico" data-price="80000">Drenaje Linfatico</option>
                    </select>
                </td>
                <td>
                    <select name="quantity[]" class="form-control">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </td>
                <td><input type="text" name="price[]" class="form-control" readonly></td>
                <td><input type="text" name="total[]" class="form-control total" autocomplete="off" readonly></td>
            </tr>`;
            $('#invoiceItem').append(newRow);

            // Desactivar los cursos generados previamente en la nueva fila
            updateCourseOptions();
        }
    });

    // Eliminar filas seleccionadas
    $('#removeRows').on('click', function() {
        if (confirm('¿Estás seguro de que quieres eliminar las filas seleccionadas?')) {
            $('#invoiceItem input.itemRow:checked').closest('tr').remove();
            calculateTotal(); // Recalcular el total después de eliminar filas
            updateCourseOptions(); // Actualizar las opciones después de eliminar filas
        }
    });

    // Función para actualizar las opciones de curso
    function updateCourseOptions() {
        var selectedCourses = [];

        // Recopila todos los cursos seleccionados en las filas actuales
        $('#invoiceItem select[name="productName[]"]').each(function() {
            var selectedCourse = $(this).val();
            if (selectedCourse) {
                selectedCourses.push(selectedCourse);
            }
        });

        // Desactiva las opciones de curso en todos los selects
        $('#invoiceItem select[name="productName[]"]').each(function() {
            var $select = $(this);
            $select.find('option').each(function() {
                var optionValue = $(this).val();
                if (selectedCourses.includes(optionValue) && optionValue !== "") {
                    $(this).prop('disabled', true);
                } else {
                    $(this).removeAttr('disabled');
                }
            });

            // Si la opción seleccionada es uno de los cursos deshabilitados, restablece la selección a "Seleccionar curso"
            var selectedValue = $select.val();
            if (selectedCourses.includes(selectedValue)) {
                $select.val('');
            }
        });

        // Asegúrate de desactivar opciones de los nuevos selects también
        $('#invoiceItem select[name="productName[]"]').each(function() {
            var $select = $(this);
            var selectedCoursesInCurrentSelect = $select.find('option:selected').map(function() {
                return $(this).val();
            }).get();
            $select.find('option').each(function() {
                var optionValue = $(this).val();
                if (selectedCourses.includes(optionValue) && optionValue !== "" && !selectedCoursesInCurrentSelect.includes(optionValue)) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).removeAttr('disabled');
                }
            });
        });
    }

    // Inicializa los precios de las filas existentes
    $('#invoiceItem select[name="productName[]"]').each(function() {
        updateCourseOptions();
    });
});
