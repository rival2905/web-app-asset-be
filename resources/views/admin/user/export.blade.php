<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.bootstrap5.css">

    <title>
        Data User UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan {{ @$filter['uptd_id'] }} 
        
    </title>
</head>

<body class="container">
<h1 class="text-center">
    Data User UPTD 
    <Br>
    Pengelolaan Jalan dan Jembatan Wilayah Pelayanan {{ @$filter['uptd_id'] }}
    <br>
    Mandor/Pekerja/Mekanik/Operator/Supir
</h1>

<table id="example" class="display table table-striped" style="width:100%">
    <thead>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Identitas</th>
        <th>Jabatan</th>
        @if (Auth::user()->id == 0)
          
        <th>Atasan</th>
        @endif
        <th>Verified</th>
        <th>Ruas</th>


    </tr>
    </thead>
    <tbody>
        
        @foreach($users as $no => $data )
        <tr>
            <td class="text-center">{{ ++$no }}</td>

            <td>
                {{ $data->name }}

            </td>
            <td>
                @if ($data->nik){{ $data->nik }}<br>@endif
                @if ($data->nip){{ $data->nip }}<br>@endif
            </td>
            <td>
                {{ @$data->jabatan }}
            </td>
            @if (Auth::user()->id == 0)
              
              <td>
                @if (@$data->mandor_id)
                Mandor : {{ @$data->mandor->name }}
                @endif
                @if ($data->mandor_id && $data->pengamat_id)
                  <br>
                @endif
                @if (@$data->pengamat_id)
                Pengamat : {{ @$data->pengamat->name }}
                @endif
              </td>
            @endif
            <td>
                {{ @$data->account_verified_at }}
            </td>
            <td>
                @if ($data->lokasi_kerja()->exists())
                @foreach ($data->lokasi_kerja as $ruas)
                    {{ $ruas->nama }};
                @endforeach
                @endif
            </td>

        </tr>
        @endforeach
    </tbody>
    </tfoot>

</table>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>

<script>
    $('#example').DataTable({
    layout: {
        topStart: {
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        }
    }
});
</script>

</body>

</html>