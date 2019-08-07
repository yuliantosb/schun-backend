<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Expense Report {{ $request->start_date }} to {{ $request->end_date }}</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <p>{{ App\Setting::getSetting()->site_name }}</p>
                    <h1>Report Expense</h1>
                    <p>Period: <small>{{ \Carbon\Carbon::parse($request->start_date)->format('d/M/Y') }} to {{ \Carbon\Carbon::parse($request->end_date)->format('d/M/Y') }}</small></p>
                </div>
                <div class="col-lg-4 text-right">
                    <img class="img img-responsive" style="width: 100px;" src="{{ App\Setting::getSetting()->file }}" alt="logo" />
                    <br />
                    <p><small>Jl. Badami Desa Margakaya Telukjambe Timur</small></p>
                </div>
            </div>
            <div class="col-lg-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Notes</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($expense->created_at)->format('m/d/Y') }}</td>
                            <td>{{ $expense->reference }} </td>
                            <td>{{ $expense->notes }}</td>
                            <td class="text-right">{{ $expense->amount_formatted }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>