@if (is_plugin_active('quanlysv'))
<h4>Danh Sách Sinh Viên</h4>
@php
$danhsachSV = get_danhsachSV();
@endphp
<table class="table" border="1">
    <thead>
        <tr>
            <th scope="col">Ảnh</th>
            <th scope="col">MSV</th>
            <th scope="col">Lớp</th>
            <th scope="col">Họ</th>
            <th scope="col">Tên</th>
            <th scope="col">Giới tính</th>
            <th scope="col">Ngày sinh</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($danhsachSV as $sv)
        <tr>
            <td>{{ $sv->anh }}</td>
            <td>{{$sv->ma_sv}}</td>
            <td>{{$sv->danhsachlops->ma_lop}}</td>
            <td>{{$sv->ho_sv}}</td>
            <td>{{$sv->ten_sv}}</td>
            <td>{{$sv->gioi_tinh}}</td>
            <td>{{$sv->ngay_sinh}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
