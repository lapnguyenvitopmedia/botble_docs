@if (is_plugin_active('quanlysv'))
<h4>Danh Sách Lớp</h4>
@php
$danhsachLop = get_danhsachLop();
@endphp
<table class="table" border="1">
    <thead>
        <tr>
            <th scope="col">Mã Lớp</th>
            <th scope="col">Chuyên Ngành</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($danhsachLop as $lop)
        <tr>
            <td>{{$lop->ma_lop}}</td>
            <td>{{$lop->chuyen_nganh}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
