<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <form id="customer-form">
        <label for="customer">Customer</label>
        <select id="customer" name="customer" style="width: 300px;">
            <!-- Options will be loaded dynamically -->
        </select>
        <button type="submit">Submit</button>
    </form>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#customer').select2({
                placeholder: 'Select a customer',
                ajax: {
                    url: '{{ route('customers.index') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                tags: true,
                createTag: function(params) {
                    var term = $.trim(params.term);

                    if (term === '') {
                        return null;
                    }

                    return {
                        id: term,
                        text: term,
                        newTag: true // add additional parameters
                    };
                }
            }).on('select2:select', function(e) {
                var data = e.params.data;

                if (data.newTag) {
                    // New customer, need to save to the server
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('customers.store') }}',
                        data: {
                            name: data.text,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            var newOption = new Option(response.text, response.id, false, true);
                            $('#customer').append(newOption).trigger('change');
                        }
                    });
                }
            });

            // Handle form submission
            $('#customer-form').on('submit', function(e) {
                e.preventDefault();

                var customerId = $('#customer').val();
                var customerName = $('#customer').find('option:selected').text();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('customer') }}', // Replace with your route name
                    data: {
                        customer_id: customerId,
                        customer_name: customerName,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Form submitted successfully!');
                        // Handle success response
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        alert('Error submitting form.');
                        // Handle error response
                    }
                });
            });

        });
    </script>

</body>

</html>
