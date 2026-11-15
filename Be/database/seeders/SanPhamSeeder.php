<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SanPhamSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'ten_san_pham' => 'Cà Phê Đen Đá',
                'slug_san_pham' => 'ca-phe-den-da',
                'ma_code' => '8934588063052',
                'mo_ta' => 'Cà phê nguyên chất pha phin truyền thống thơm ngon đậm đà',
                'kich_thuoc' => 'M',
                'gia_ban' => 750,
                'hinh_anh' => 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 1
            ],
            [
                'id' => 2,
                'ten_san_pham' => 'Cà Phê Sữa Đá',
                'slug_san_pham' => 'ca-phe-sua-da',
                'ma_code' => '8934588063100',
                'mo_ta' => 'Cà phê đen kết hợp sữa đặc Vinamilk thơm ngậy ngọt ngào',
                'kich_thuoc' => 'M',
                'gia_ban' => 900,
                'hinh_anh' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 1
            ],
            [
                'id' => 3,
                'ten_san_pham' => 'Bạc Xỉu',
                'slug_san_pham' => 'bac-xiu',
                'ma_code' => '8934588063054',
                'mo_ta' => 'Nhiều sữa ít cà phê, béo ngậy ngon miệng',
                'kich_thuoc' => 'M',
                'gia_ban' => 900,
                'hinh_anh' => 'https://th.bing.com/th/id/OIP.ltSiYBWdKaQQl0umgWINxwHaE5?w=266&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'tinh_trang' => 1,
                'id_danh_muc' => 1
            ],
            [
                'id' => 4,
                'ten_san_pham' => 'Cà Phê Muối',
                'slug_san_pham' => 'ca-phe-muoi',
                'ma_code' => '8934588063055',
                'mo_ta' => 'Sự kết hợp hoàn hảo giữa cà phê đậm đà và kem mặn béo ngậy',
                'kich_thuoc' => 'M',
                'gia_ban' => 950,
                'hinh_anh' => 'https://images.unsplash.com/photo-1578314675249-a6910f80cc4e?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 1
            ],
            [
                'id' => 5,
                'ten_san_pham' => 'Cà Phê Trứng',
                'slug_san_pham' => 'ca-phe-trung',
                'ma_code' => '8934588063056',
                'mo_ta' => 'Lớp kem trứng bông mịn thơm béo ngậy trên nền cà phê phin đậm đặc',
                'kich_thuoc' => 'M',
                'gia_ban' => 1000,
                'hinh_anh' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 1
            ],
            [
                'id' => 6,
                'ten_san_pham' => 'Espresso Nóng',
                'slug_san_pham' => 'espresso-nong',
                'ma_code' => '8934588063057',
                'mo_ta' => 'Chiết xuất từ hạt cà phê xay mịn bằng áp suất nước nóng thơm quyến rũ',
                'kich_thuoc' => 'S',
                'gia_ban' => 750,
                'hinh_anh' => 'https://th.bing.com/th/id/OIP.AuIRGWY8C0d8zD6leQKEaAHaEo?w=284&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'tinh_trang' => 1,
                'id_danh_muc' => 1
            ],
            [
                'id' => 7,
                'ten_san_pham' => 'Americano Đá',
                'slug_san_pham' => 'americano-da',
                'ma_code' => '8934588063058',
                'mo_ta' => 'Espresso pha loãng với nước đá mang lại hương vị nhẹ nhàng, sảng khoái',
                'kich_thuoc' => 'L',
                'gia_ban' => 800,
                'hinh_anh' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 1
            ],
            [
                'id' => 8,
                'ten_san_pham' => 'Cappuccino',
                'slug_san_pham' => 'cappuccino',
                'ma_code' => '8934588063059',
                'mo_ta' => 'Espresso kết hợp sữa nóng và lớp bọt sữa dày mịn rắc bột cacao',
                'kich_thuoc' => 'M',
                'gia_ban' => 1000,
                'hinh_anh' => 'https://images.unsplash.com/photo-1534778101976-62847782c213?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 1
            ],
            [
                'id' => 9,
                'ten_san_pham' => 'Latte Nóng',
                'slug_san_pham' => 'latte-nong',
                'ma_code' => '8934588063060',
                'mo_ta' => 'Espresso với nhiều sữa nóng tạo hình nghệ thuật mượt mà béo ngậy',
                'kich_thuoc' => 'M',
                'gia_ban' => 1000,
                'hinh_anh' => 'https://th.bing.com/th/id/OIP.yZZw8kJgUo1mDVIyoDoLsQHaG3?w=204&h=190&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'tinh_trang' => 1,
                'id_danh_muc' => 1
            ],
            [
                'id' => 10,
                'ten_san_pham' => 'Cà Phê Dừa',
                'slug_san_pham' => 'ca-phe-dua',
                'ma_code' => '8934588063061',
                'mo_ta' => 'Cà phê đậm vị hòa quyện cùng cốt dừa xay đá tuy ngọt bùi sảng khoái',
                'kich_thuoc' => 'M',
                'gia_ban' => 950,
                'hinh_anh' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 1
            ],

            // TRÀ & TRÀ SỮA (10 món)
            [
                'id' => 11,
                'ten_san_pham' => 'Trà Đào Hồng Đài',
                'slug_san_pham' => 'tra-dao-hong-dai',
                'ma_code' => '8934588063062',
                'mo_ta' => 'Trà đen thơm kết hợp syrup đào thượng hạng và topping đào lát giòn ngon',
                'kich_thuoc' => 'L',
                'gia_ban' => 900,
                'hinh_anh' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 2
            ],
            [
                'id' => 12,
                'ten_san_pham' => 'Hồng Trà Sữa',
                'slug_san_pham' => 'hong-tra-sua',
                'ma_code' => '8934588063063',
                'mo_ta' => 'Trà đen sữa đậm vị trà ngậy vị sữa đặc biệt',
                'kich_thuoc' => 'L',
                'gia_ban' => 850,
                'hinh_anh' => 'https://images.unsplash.com/photo-1541658016709-82535e94bc69?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 2
            ],
            [
                'id' => 13,
                'ten_san_pham' => 'Trà Sữa Trân Châu Đường Đen',
                'slug_san_pham' => 'tra-sua-tran-chau-duong-den',
                'ma_code' => '8934588063064',
                'mo_ta' => 'Trà sữa truyền thống béo ngậy ăn kèm trân châu đen ngào đường thơm phức',
                'kich_thuoc' => 'L',
                'gia_ban' => 950,
                'hinh_anh' => 'https://images.unsplash.com/photo-1541658016709-82535e94bc69?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 2
            ],
            [
                'id' => 14,
                'ten_san_pham' => 'Trà Sữa Matcha',
                'slug_san_pham' => 'tra-sua-matcha',
                'ma_code' => '8934588063065',
                'mo_ta' => 'Bột trà xanh Uji Nhật Bản nguyên chất béo ngậy dậy vị trà đậm đà',
                'kich_thuoc' => 'M',
                'gia_ban' => 900,
                'hinh_anh' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 2
            ],
            [
                'id' => 15,
                'ten_san_pham' => 'Trà Sữa Ô Long',
                'slug_san_pham' => 'tra-sua-o-long',
                'ma_code' => '8934588063066',
                'mo_ta' => 'Trà ô long thượng hạng sữa béo đậm đà thanh mát tinh tế',
                'kich_thuoc' => 'L',
                'gia_ban' => 850,
                'hinh_anh' => 'https://images.unsplash.com/photo-1541658016709-82535e94bc69?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 2
            ],
            [
                'id' => 16,
                'ten_san_pham' => 'Trà Thiết Quan Âm',
                'slug_san_pham' => 'tra-thiet-quan-am',
                'ma_code' => '8934588063067',
                'mo_ta' => 'Trà Thiết Quan Âm ủ lạnh thanh mát hương hoa cỏ dịu nhẹ tự nhiên',
                'kich_thuoc' => 'M',
                'gia_ban' => 800,
                'hinh_anh' => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 2
            ],
            [
                'id' => 17,
                'ten_san_pham' => 'Trà Vải Nhài',
                'slug_san_pham' => 'tra-vai-nhai',
                'ma_code' => '8934588063068',
                'mo_ta' => 'Trà lài thanh mát hòa quyện hương vải ngọt ngào kèm topping quả vải giòn ngọt',
                'kich_thuoc' => 'L',
                'gia_ban' => 900,
                'hinh_anh' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 2
            ],
            [
                'id' => 18,
                'ten_san_pham' => 'Trà Mãng Cầu',
                'slug_san_pham' => 'tra-mang-cau',
                'ma_code' => '8934588063069',
                'mo_ta' => 'Trà thanh nhiệt kết hợp mứt mãng cầu xiêm chua ngọt giòn dai đã thèm',
                'kich_thuoc' => 'L',
                'gia_ban' => 900,
                'hinh_anh' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 2
            ],
            [
                'id' => 19,
                'ten_san_pham' => 'Trà Sen Vàng',
                'slug_san_pham' => 'tra-sen-vang',
                'ma_code' => '8934588063070',
                'mo_ta' => 'Trà ô long sen thơm mát kết hợp hạt sen bùi ngậy và củ năng giòn ngọt',
                'kich_thuoc' => 'L',
                'gia_ban' => 1000,
                'hinh_anh' => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 2
            ],
            [
                'id' => 20,
                'ten_san_pham' => 'Trà Dâu Macchiato',
                'slug_san_pham' => 'tra-dau-macchiato',
                'ma_code' => '8934588063071',
                'mo_ta' => 'Nước trà dâu đỏ mọng thơm ngọt kết hợp lớp kem sữa béo ngậy bên trên',
                'kich_thuoc' => 'L',
                'gia_ban' => 950,
                'hinh_anh' => 'https://images.unsplash.com/photo-1595981267035-7b04ca84a82d?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 2
            ],

            // ĐÁ XAY (10 món)
            [
                'id' => 21,
                'ten_san_pham' => 'Matcha Đá Xay',
                'slug_san_pham' => 'matcha-da-xay',
                'ma_code' => '8934588063072',
                'mo_ta' => 'Trà xanh Nhật Bản đá xay kem tươi mịn màng thơm béo',
                'kich_thuoc' => 'M',
                'gia_ban' => 1000,
                'hinh_anh' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 3
            ],
            [
                'id' => 22,
                'ten_san_pham' => 'Cà Phê Cốt Dừa Đá Xay',
                'slug_san_pham' => 'ca-phe-cot-dua-da-xay',
                'ma_code' => '8934588063073',
                'mo_ta' => 'Cà phê espresso đậm đà xay đá cùng cốt dừa sữa đặc béo ngậy ngọt bùi',
                'kich_thuoc' => 'M',
                'gia_ban' => 950,
                'hinh_anh' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 3
            ],
            [
                'id' => 23,
                'ten_san_pham' => 'Sô-cô-la Đá Xay',
                'slug_san_pham' => 'so-co-la-da-xay',
                'ma_code' => '8934588063074',
                'mo_ta' => 'Sô-cô-la Bỉ nguyên chất đắng ngọt hài hòa xay đá phủ kem tươi béo mịn',
                'kich_thuoc' => 'M',
                'gia_ban' => 1000,
                'hinh_anh' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 3
            ],
            [
                'id' => 24,
                'ten_san_pham' => 'Cookie Cream Đá Xay',
                'slug_san_pham' => 'cookie-cream-da-xay',
                'ma_code' => '8934588063075',
                'mo_ta' => 'Bánh Oreo xay cùng sữa tươi, sữa béo ngọt ngào quyến rũ kèm sốt sô-cô-la',
                'kich_thuoc' => 'M',
                'gia_ban' => 1000,
                'hinh_anh' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 3
            ],
            [
                'id' => 25,
                'ten_san_pham' => 'Việt Quất Đá Xay',
                'slug_san_pham' => 'viet-quat-da-xay',
                'ma_code' => '8934588063076',
                'mo_ta' => 'Mứt việt quất chua chua ngọt ngọt xay nhuyễn mát lạnh cùng sữa chua mịn màng',
                'kich_thuoc' => 'M',
                'gia_ban' => 1000,
                'hinh_anh' => 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 3
            ],
            [
                'id' => 26,
                'ten_san_pham' => 'Dâu Tây Đá Xay',
                'slug_san_pham' => 'dau-tay-da-xay',
                'ma_code' => '8934588063077',
                'mo_ta' => 'Sinh tố dâu tây xay mịn màng mát lạnh phủ kem tươi ngọt ngào rực rỡ',
                'kich_thuoc' => 'M',
                'gia_ban' => 1000,
                'hinh_anh' => 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 3
            ],
            [
                'id' => 27,
                'ten_san_pham' => 'Chanh Tuyết',
                'slug_san_pham' => 'chanh-tuyet',
                'ma_code' => '8934588063078',
                'mo_ta' => 'Nước cốt chanh tươi xay đá tuyết sủi bọt trắng xóa thanh nhiệt giải độc ngày hè',
                'kich_thuoc' => 'L',
                'gia_ban' => 750,
                'hinh_anh' => 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 3
            ],
            [
                'id' => 28,
                'ten_san_pham' => 'Phúc Bồn Tử Đá Xay',
                'slug_san_pham' => 'phuc-bon-tu-da-xay',
                'ma_code' => '8934588063079',
                'mo_ta' => 'Sự kết hợp ngọt ngào chua dịu từ quả phúc bồn tử xay mịn thơm mát lạnh',
                'kich_thuoc' => 'M',
                'gia_ban' => 1000,
                'hinh_anh' => 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 3
            ],
            [
                'id' => 29,
                'ten_san_pham' => 'Xoài Đá Xay',
                'slug_san_pham' => 'xoai-da-xay',
                'ma_code' => '8934588063080',
                'mo_ta' => 'Xoài chín cát Hoà Lộc ngọt lịm thơm mát xay mịn màng mát lạnh đã khát',
                'kich_thuoc' => 'M',
                'gia_ban' => 950,
                'hinh_anh' => 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 3
            ],
            [
                'id' => 30,
                'ten_san_pham' => 'Caramel Đá Xay',
                'slug_san_pham' => 'caramel-da-xay',
                'ma_code' => '8934588063081',
                'mo_ta' => 'Sốt Caramel ngọt ngào xay nhuyễn cùng đá và cà phê phủ whipping cream béo ngậy',
                'kich_thuoc' => 'M',
                'gia_ban' => 1000,
                'hinh_anh' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 3
            ],

            // BÁNH NGỌT (10 món)
            [
                'id' => 31,
                'ten_san_pham' => 'Bánh Tiramisu',
                'slug_san_pham' => 'banh-tiramisu',
                'ma_code' => '8934588063082',
                'mo_ta' => 'Bánh ngọt phô mai cacao Ý mềm mịn quyến rũ',
                'kich_thuoc' => 'S',
                'gia_ban' => 900,
                'hinh_anh' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 4
            ],
            [
                'id' => 32,
                'ten_san_pham' => 'Bánh Mousse Chanh Leo',
                'slug_san_pham' => 'banh-mousse-chanh-leo',
                'ma_code' => '8934588063083',
                'mo_ta' => 'Mousse chanh leo chua thanh nhẹ nhàng kết hợp lớp kem sữa mịn màng ngậy béo',
                'kich_thuoc' => 'S',
                'gia_ban' => 800,
                'hinh_anh' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 4
            ],
            [
                'id' => 33,
                'ten_san_pham' => 'Bánh Croissant Bơ Tỏi',
                'slug_san_pham' => 'banh-croissant-bo-toi',
                'ma_code' => '8934588063084',
                'mo_ta' => 'Bánh sừng bò ngàn lớp nướng bơ tỏi thơm lừng giòn tan hấp dẫn',
                'kich_thuoc' => 'M',
                'gia_ban' => 750,
                'hinh_anh' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 4
            ],
            [
                'id' => 34,
                'ten_san_pham' => 'Bánh Red Velvet',
                'slug_san_pham' => 'banh-red-velvet',
                'ma_code' => '8934588063085',
                'mo_ta' => 'Bánh bông lan nhung đỏ mềm xốp kết hợp kem tươi phô mai thơm ngậy quyến rũ',
                'kich_thuoc' => 'S',
                'gia_ban' => 900,
                'hinh_anh' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 4
            ],
            [
                'id' => 35,
                'ten_san_pham' => 'Cheesecake Việt Quất',
                'slug_san_pham' => 'cheesecake-viet-quat',
                'ma_code' => '8934588063086',
                'mo_ta' => 'Bánh phô mai nướng ngậy béo phủ sốt việt quất chua ngọt đậm đà hấp dẫn',
                'kich_thuoc' => 'S',
                'gia_ban' => 950,
                'hinh_anh' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 4
            ],
            [
                'id' => 36,
                'ten_san_pham' => 'Bánh Mì Hoa Cúc',
                'slug_san_pham' => 'banh-mi-hoa-cuc',
                'ma_code' => '8934588063087',
                'mo_ta' => 'Bánh mì bơ Pháp thơm lừng hương hoa cúc dai mềm béo ngậy ngọt ngào',
                'kich_thuoc' => 'M',
                'gia_ban' => 600,
                'hinh_anh' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 4
            ],
            [
                'id' => 37,
                'ten_san_pham' => 'Bánh Donut Dâu Tây',
                'slug_san_pham' => 'banh-donut-dau-tay',
                'ma_code' => '8934588063088',
                'mo_ta' => 'Bánh vòng Donut chiên phồng phủ lớp chocolate dâu ngọt ngào rực rỡ sắc màu',
                'kich_thuoc' => 'S',
                'gia_ban' => 500,
                'hinh_anh' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 4
            ],
            [
                'id' => 38,
                'ten_san_pham' => 'Bánh Su Kem',
                'slug_san_pham' => 'banh-su-kem',
                'ma_code' => '8934588063089',
                'mo_ta' => 'Bánh su kem vỏ mềm mịn chứa đầy nhân kem vani mát lạnh ngọt thanh béo ngậy',
                'kich_thuoc' => 'S',
                'gia_ban' => 400,
                'hinh_anh' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 4
            ],
            [
                'id' => 39,
                'ten_san_pham' => 'Bánh Waffle Mật Ong',
                'slug_san_pham' => 'banh-waffle-mat-ong',
                'ma_code' => '8934588063090',
                'mo_ta' => 'Bánh tổ ong nướng nóng hổi ăn kèm mật ong rừng và bơ thơm ngậy',
                'kich_thuoc' => 'M',
                'gia_ban' => 750,
                'hinh_anh' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 4
            ],
            [
                'id' => 40,
                'ten_san_pham' => 'Bánh Mì Phô Mai Chảy',
                'slug_san_pham' => 'banh-mi-pho-mai-chay',
                'ma_code' => '8934588063091',
                'mo_ta' => 'Bánh mì xốp mềm ngập nhân sốt kem phô mai béo ngậy tan chảy đầy đặn chà bông',
                'kich_thuoc' => 'M',
                'gia_ban' => 900,
                'hinh_anh' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 4
            ],
            [
                'id' => 41,
                'ten_san_pham' => 'Nước Lọc Aquafina Chai',
                'slug_san_pham' => 'nuoc-loc-aquafina-chai',
                'ma_code' => '8934588063053',
                'mo_ta' => 'Nước uống tinh khiết Aquafina đóng chai thanh mát và sạch sẽ',
                'kich_thuoc' => 'M',
                'gia_ban' => 500,
                'hinh_anh' => 'https://images.unsplash.com/photo-1608885898957-a599fb16ec18?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 5
            ],
            [
                'id' => 42,
                'ten_san_pham' => 'Aquafina Soda',
                'slug_san_pham' => 'aquafina-soda',
                'ma_code' => '8934588692116',
                'mo_ta' => 'Nước uống có ga Soda Aquafina thanh mát sảng khoái',
                'kich_thuoc' => 'M',
                'gia_ban' => 600,
                'hinh_anh' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 5
            ],
            [
                'id' => 43,
                'ten_san_pham' => 'Pepsi',
                'slug_san_pham' => 'pepsi',
                'ma_code' => '8934588012112',
                'mo_ta' => 'Nước ngọt Pepsi giải khát có ga sảng khoái',
                'kich_thuoc' => 'M',
                'gia_ban' => 750,
                'hinh_anh' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 5
            ],
            [
                'id' => 44,
                'ten_san_pham' => '7Up',
                'slug_san_pham' => '7up',
                'ma_code' => '8934588022111',
                'mo_ta' => 'Nước ngọt 7Up vị chanh thanh mát có ga giải khát',
                'kich_thuoc' => 'M',
                'gia_ban' => 750,
                'hinh_anh' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 5
            ],
            [
                'id' => 45,
                'ten_san_pham' => 'Sting Đỏ',
                'slug_san_pham' => 'sting-do',
                'ma_code' => '8934588232114',
                'mo_ta' => 'Nước tăng lực Sting hương dâu đỏ ngọt ngào sảng khoái',
                'kich_thuoc' => 'M',
                'gia_ban' => 750,
                'hinh_anh' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 5
            ],
            [
                'id' => 46,
                'ten_san_pham' => 'Sting Vàng',
                'slug_san_pham' => 'sting-vang',
                'ma_code' => '8934588172113',
                'mo_ta' => 'Nước tăng lực Sting hương nhân sâm sảng khoái tỉnh táo',
                'kich_thuoc' => 'M',
                'gia_ban' => 750,
                'hinh_anh' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 5
            ],
            [
                'id' => 47,
                'ten_san_pham' => 'Trà Chanh Yuzu',
                'slug_san_pham' => 'tra-chanh-yuzu',
                'ma_code' => '8934588923555',
                'mo_ta' => 'Trà chanh kết hợp hương vị quả Yuzu thơm mát sảng khoái',
                'kich_thuoc' => 'M',
                'gia_ban' => 750,
                'hinh_anh' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=500&auto=format&fit=crop&q=80',
                'tinh_trang' => 1,
                'id_danh_muc' => 5
            ],
        ];

        DB::table('san_phams')->insert($data);
    }
}
