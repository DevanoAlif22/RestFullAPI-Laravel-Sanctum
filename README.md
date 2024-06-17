<h1>Dokumentasi RESTFull API Laravel 10 Sanctum</h1>h1>
<p>Berikut adalah dokumentasi pembuatan RESTFull API menggunakan laravel 10 dengan validasi atau authentikasi menggunakan Laracel Sanctum</p>
<p>Penjelasan secara singkat seperti berikut : </p>
<ul>
    <li>Studi kasusnya adalah Product (DB lewat migration / import folder DB) </li>
    <li>Terdapat 2 ability atau role yaitu admin dan user</li>
    <li>Role admin dapat melakukan CRUD Product</li>
    <li>Role user hanya bisa melakukan Read Product</li>
    <li>Terdapat 4 method yaitu GET,POST,PACTH dan DELETE</li>
    <li>Ada 7 Request Login,Register,List Product,Store,Edit dan Delete Product</li>
    <li>Dimana setiap Request List Product, dan CRUD Product diharuskan membawa token</li>
    <li>Token kadaluarsa di 6 menit kedepan (bisa di edit di AuthController.php</li>
</ul>
