@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Salary Case') }}</div>

                <div class="card-body">
                    <input type="number" id="monthly_income" placeholder="Enter Monthly Income">
                    <button id="calculateBtn">Calculate</button>

                    <div id="results" style="margin-top:20px;">
                        <!-- Result will appear here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){

    $('#calculateBtn').on('click', function () {
        let income = $('#monthly_income').val();
        var num = true
        if(income == '' && income <= 0){
            alert('Please Correct Value');
            return false;
        }
        $.ajax({
            url: '{{ route("calculate.tax") }}',
            method: 'POST',
            data: {
                income: income,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                $('#results').html(`
                    <p><strong>Monthly Income:</strong> ${response.monthly_income}</p>
                    <p><strong>Monthly Tax:</strong> ${response.monthly_tax}</p>
                    <p><strong>Salary After Tax:</strong> ${response.salary_after_tax}</p>
                    <p><strong>Yearly Income:</strong> ${response.yearly_income}</p>
                    <p><strong>Yearly Tax:</strong> ${response.yearly_tax}</p>
                    <p><strong>Yearly Income After Tax:</strong> ${response.yearly_income_after_tax}</p>
                `);
            }
        });
    });
});
    
</script>
@endsection