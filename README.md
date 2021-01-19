
Botble cms được xây dựng trên nền tảng laravel framework. Cms này thường được sử dụng cho những dự án chuyên về web thông tin.
Khi sử dụng Botble ta tận dụng được plugin Blog của nó. Những yêu cầu thêm ngoài blog thì ta phải viết plugin. 

Document của Botble ở đây https://docs.botble.com/cms/ 

Đối với những bạn mới coding làm quen với cms này thì nên tập trung vào những mục sau:  

Plugin blog có sẵn 
------------------
	Cách overwrite file .blade của plugin này. Vd theme sẽ sử dụng file 
	platform/theme/{theme name}/view/category.blade.php (1) 
	thay cho platform/plugins/blog/resources/view/themes/category.blade.php nếu	file (1) tồn tại 

Plugin 
------
	Botble có sẵn hệ thống CRUD, các bạn có thể tận dụng. Đánh lệnh cms:package:make:crud để gennerate. 
	Để display content của plugin mà bạn muốn thì phải dùng shortcode hay dùng những hàm trong helper
	của plugin đó 

Theme
-----
	Layout 
	Cú pháp trong template giống như trong Laravel https://laravel.com/docs/8.x/blade 
	Cách chèn js và css 
	Sau khi chỉnh sửa js, css nhớ chạy lệnh php artisan cms:theme:assets:publish 

Widget
------

Shortcode
---------

Custom fields
-------------