<!-- Footer - Mobile Friendly -->
<footer class="bg-white border-t border-gray-200">
    <div class="container mx-auto px-1 md:px-1 py-2 md:py-2">
        <!-- Desktop Layout -->
        <div class="hidden md:flex justify-between items-center">
            <!-- Left - Copyright -->
            <div class="text-xs text-gray-500">
                © 2015 - {{ date('Y') }} Hak cipta terpelihara - Pertubuhan Pengurusan Jenazah Ummah Bintulu
            </div>
            
            <!-- Right - Links -->
            <div class="flex space-x-4">
                <a href="#" class="footer-modal-link text-xs text-gray-500 hover:text-gray-700 transition-colors" data-modal-key="penafian" data-modal-title="Penafian">Penafian</a>
                <span class="text-xs text-gray-400">/</span>
                <a href="#" class="footer-modal-link text-xs text-gray-500 hover:text-gray-700 transition-colors" data-modal-key="privasi" data-modal-title="Privasi">Privasi</a>
                <span class="text-xs text-gray-400">/</span>
                <a href="#" class="footer-modal-link text-xs text-gray-500 hover:text-gray-700 transition-colors" data-modal-key="terma" data-modal-title="Terma Penggunaan">Terma Penggunaan</a>
                <span class="text-xs text-gray-400">/</span>
                <a href="#" class="footer-modal-link text-xs text-gray-500 hover:text-gray-700 transition-colors" data-modal-key="peta" data-modal-title="Peta Laman">Peta Laman</a>
            </div>
        </div>
        
        <!-- Mobile Layout -->
        <div class="md:hidden space-y-4">
            <!-- Mobile - Copyright -->
            <div class="text-center">
                <div class="text-xs text-gray-500 mb-2">
                    © 2015 - {{ date('Y') }} Hak cipta terpelihara
                </div>
                <div class="text-xs text-gray-400">
                    Pertubuhan Pengurusan Jenazah Ummah Bintulu
                </div>
            </div>
            
            <!-- Mobile - Links Grid -->
            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-100">
                <a href="#" class="footer-modal-link text-xs text-gray-500 hover:text-gray-700 transition-colors text-center py-2 px-3 bg-gray-50 rounded-lg" data-modal-key="penafian" data-modal-title="Penafian">
                    Penafian
                </a>
                <a href="#" class="footer-modal-link text-xs text-gray-500 hover:text-gray-700 transition-colors text-center py-2 px-3 bg-gray-50 rounded-lg" data-modal-key="privasi" data-modal-title="Privasi">
                    Privasi
                </a>
                <a href="#" class="footer-modal-link text-xs text-gray-500 hover:text-gray-700 transition-colors text-center py-2 px-3 bg-gray-50 rounded-lg" data-modal-key="terma" data-modal-title="Terma Penggunaan">
                    Terma Penggunaan
                </a>
                <a href="#" class="footer-modal-link text-xs text-gray-500 hover:text-gray-700 transition-colors text-center py-2 px-3 bg-gray-50 rounded-lg" data-modal-key="peta" data-modal-title="Peta Laman">
                    Peta Laman
                </a>
            </div>
            
            <!-- Mobile - Quick Info -->
            <div class="text-center pt-3 border-t border-gray-100">
                <div class="text-xs text-gray-400">
                    Versi 1.0.0 | Kemaskini Terakhir: {{ date('d/m/Y') }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reusable Footer Modal -->
    <div id="footerModal" class="hidden fixed inset-0 z-50 items-center justify-center">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-[1px]" id="footerModalBackdrop"></div>
        <div class="relative bg-white mx-auto rounded-xl shadow-2xl overflow-hidden text-xs flex flex-col" style="width:1024px !important; max-width:none !important; max-height:85vh !important;">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 id="footerModalTitle" class="text-sm font-semibold text-gray-800">Maklumat</h3>
                <button id="footerModalClose" class="text-gray-500 hover:text-gray-700 text-sm leading-none transition transform hover:rotate-90">&times;</button>
            </div>
            <div class="p-6 overflow-y-auto" style="max-height: calc(85vh - 56px);">
                <div id="footerModalContent" class="prose prose-xs max-w-none text-gray-700 font-normal">
                    Kandungan ringkas.
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('footerModal');
        const titleEl = document.getElementById('footerModalTitle');
        const contentEl = document.getElementById('footerModalContent');
        const closeBtn = document.getElementById('footerModalClose');
        const backdrop = document.getElementById('footerModalBackdrop');
        
        const CONTENT_MAP = {
            penafian: `
                <h4 class="font-semibold mb-2">Penafian (Disclaimer) – Sistem Aplikasi Laravel PPJUB</h4>
                <p class="mb-3">Dokumen penafian ini merujuk khusus kepada penggunaan sistem aplikasi dalaman PPJUB yang berasaskan Laravel (selepas ini dirujuk sebagai "Sistem"). Penafian ini melengkapi penafian untuk laman web awam dan bertujuan menjelaskan skop, had, dan tanggungjawab semua pihak yang terlibat dalam penggunaan Sistem oleh pentadbir, operator, sukarelawan, pembekal perkhidmatan, serta pengguna yang diberi kebenaran.</p>
                <h5 class="font-semibold mt-3 mb-1">1. Tujuan, Skop dan Sifat Sistem</h5>
                <p class="mb-2">Sistem disediakan untuk menyokong operasi pentadbiran PPJUB termasuk pengurusan rekod, penyelarasan data, komunikasi dalaman, dan pemantauan operasi. Walaupun usaha munasabah telah diambil untuk memastikan Sistem stabil, selamat, dan berfungsi seperti yang direka, Sistem disediakan "seadanya" tanpa sebarang jaminan tersurat atau tersirat berhubung prestasi, kebolehpercayaan, keserasian, atau kesesuaian untuk tujuan tertentu.</p>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>Fungsi, modul, dan integrasi pihak ketiga boleh berubah dari semasa ke semasa.</li>
                    <li>Antara muka pengguna mungkin berbeza mengikut versi, peranan pengguna, dan konfigurasi persekitaran.</li>
                    <li>Sebahagian ciri memerlukan konfigurasi sistem luaran (contoh: e‑mel SMTP, token API) yang berada di luar kawalan PPJUB.</li>
                </ul>
                <h5 class="font-semibold mt-3 mb-1">2. Ketepatan Data, Kemaskini dan Sumber</h5>
                <p class="mb-2">Data yang diproses dan dipaparkan dalam Sistem lazimnya datang daripada input pengguna yang diberi kuasa, data waris, rekod operasi lapangan, atau integrasi dengan perkhidmatan lain yang dibenarkan. Walaupun proses semakan wujud, ralat data, percanggahan, atau kelewatan kemaskini boleh berlaku.</p>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>PPJUB tidak menjamin bahawa setiap data adalah lengkap atau terkini pada setiap masa.</li>
                    <li>Bagi tujuan pengesahan rasmi, rujuk dokumen asal yang dikeluarkan pihak berkuasa/agensi berkenaan.</li>
                    <li>Penyatuan data mungkin melibatkan normalisasi yang menyebabkan variasi paparan berbanding sumber asal.</li>
                </ul>
                <h5 class="font-semibold mt-3 mb-1">3. Akses, Kebenaran dan Tanggungjawab Pengguna</h5>
                <p class="mb-2">Akses ke Sistem adalah berdasarkan peranan dan kebenaran yang ditetapkan. Pengguna bertanggungjawab untuk menjaga kerahsiaan kelayakan log masuk, token akses, dan sebarang kaedah pengesahan lain.</p>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>Jangan berkongsi kata laluan atau token dengan pihak tidak berautoriti.</li>
                    <li>Sebarang aktiviti yang dilakukan menggunakan akaun anda dianggap dilakukan oleh anda.</li>
                    <li>Laporan segera perlu dibuat jika terdapat syak wasangka pencerobohan, kehilangan peranti, atau pendedahan kelayakan.</li>
                </ul>
                <h5 class="font-semibold mt-3 mb-1">4. Keselamatan Aplikasi dan Infrastruktur</h5>
                <p class="mb-2">PPJUB melaksanakan langkah keselamatan yang munasabah seperti kawalan akses berasaskan peranan, perlindungan token, dan semakan log. Walau begitu, tiada sistem yang boleh dijamin 100% bebas risiko.</p>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>Ancaman siber, kelemahan perisian, atau kegagalan perkakasan boleh berlaku tanpa diduga.</li>
                    <li>Kemas kini keselamatan mungkin memerlukan henti tugas sementara (downtime) yang munasabah.</li>
                    <li>Penggunaan rangkaian awam/tidak selamat oleh pengguna boleh menjejaskan keselamatan data.</li>
                </ul>
                <h5 class="font-semibold mt-3 mb-1">5. Integrasi Pihak Ketiga dan Perkhidmatan Luaran</h5>
                <p class="mb-2">Sistem mungkin berintegrasi dengan perkhidmatan luaran (contoh: e‑mel SMTP, perkhidmatan peta, API kerajaan, storan objek). PPJUB tidak mengawal dasar, ketersediaan, atau perubahan teknikal yang dibuat oleh pembekal tersebut.</p>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>Kegagalan atau perubahan mendadak pada perkhidmatan luaran boleh menjejaskan fungsi tertentu.</li>
                    <li>Had kadar (rate limit), polisi privasi, dan terma pembekal mestilah dipatuhi pengguna apabila berkaitan.</li>
                </ul>
                <h5 class="font-semibold mt-3 mb-1">6. Had Tanggungjawab</h5>
                <p class="mb-2">Dalam apa jua keadaan, PPJUB, pegawai, sukarelawan, kontraktor, atau wakilnya tidak bertanggungjawab atas sebarang kerugian langsung, tidak langsung, sampingan, istimewa atau berbangkit yang timbul akibat penggunaan atau ketidakupayaan menggunakan Sistem, termasuk tetapi tidak terhad kepada kehilangan data, gangguan perkhidmatan, salah tafsir maklumat, atau kegagalan penghantaran e‑mel/notifikasi.</p>
                <h5 class="font-semibold mt-3 mb-1">7. Privasi, Perlindungan Data dan Kerahsiaan</h5>
                <p class="mb-2">Pemprosesan data peribadi dalam Sistem mematuhi prinsip yang munasabah selaras dengan undang‑undang Malaysia. Akses kepada data adalah terhad mengikut peranan dan keperluan tugas.</p>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>Jangan memuat turun, mengeksport, atau berkongsi data di luar tujuan kerja yang dibenarkan.</li>
                    <li>Data sensitif hendaklah dikendalikan mengikut garis panduan PPJUB dan undang‑undang yang terpakai.</li>
                    <li>Rujuk Dasar Privasi untuk butiran lanjut mengenai penyimpanan, pemegangan, dan pendedahan data.</li>
                </ul>
                <h5 class="font-semibold mt-3 mb-1">8. Kualiti Perkhidmatan, Penyelenggaraan dan Ketersediaan</h5>
                <p class="mb-2">PPJUB berusaha mengekalkan ketersediaan Sistem pada tahap yang munasabah. Walau bagaimanapun, penyelenggaraan berjadual, kemas kini, migrasi, atau faktor di luar kawalan mungkin menyebabkan gangguan sementara.</p>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>Notifikasi akan diberikan jika wajar dan praktikal.</li>
                    <li>Data yang disimpan semasa gangguan mungkin tertakluk kepada kelewatan pemprosesan.</li>
                </ul>
                <h5 class="font-semibold mt-3 mb-1">9. Hak Cipta, Lesen dan Harta Intelek</h5>
                <p class="mb-2">Kod sumber, reka bentuk antaramuka, dokumentasi, dan kandungan Sistem adalah milik PPJUB kecuali dinyatakan sebaliknya. Sebarang penggandaan, pengubahsuaian atau pengedaran kandungan Sistem tanpa kebenaran bertulis adalah dilarang.</p>
                <h5 class="font-semibold mt-3 mb-1">10. Amalan Penggunaan Yang Dibenarkan</h5>
                <p class="mb-2">Pengguna hendaklah mematuhi garis panduan berikut semasa menggunakan Sistem:</p>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>Tidak mengeksploitasi kelemahan atau cuba mendapatkan akses tanpa kebenaran.</li>
                    <li>Tidak memuat naik kandungan berniat jahat seperti perisian hasad (malware) atau skrip berbahaya.</li>
                    <li>Tidak menjalankan aktiviti automasi (bot/scraping) tanpa kebenaran bertulis.</li>
                    <li>Memastikan peranti yang digunakan dilindungi dengan baik dan dikemas kini.</li>
                </ul>
                <h5 class="font-semibold mt-3 mb-1">11. Notis Perundangan dan Penguatkuasaan</h5>
                <p class="mb-2">Sebarang cubaan pencerobohan, sabotaj, penyalahgunaan data, atau pelanggaran undang‑undang akan dirujuk kepada pihak berkuasa yang berkaitan. PPJUB berhak menggantung atau menamatkan akses pengguna yang melanggar terma tanpa notis.</p>
                <h5 class="font-semibold mt-3 mb-1">12. Perubahan Penafian dan Interpretasi</h5>
                <p class="mb-2">Penafian ini boleh dikemas kini dari semasa ke semasa tanpa notis. Versi terbaharu yang dipaparkan dalam Sistem akan mengatasi sebarang versi terdahulu. Jika terdapat percanggahan tafsiran, versi Bahasa Melayu dalam Sistem hendaklah diguna pakai.</p>
                <h5 class="font-semibold mt-3 mb-1">13. Pertanyaan dan Saluran Rasmi</h5>
                <p class="mb-3">Untuk pertanyaan teknikal, aduan keselamatan, atau permintaan berkaitan data, sila hubungi sekretariat PPJUB melalui saluran rasmi yang ditetapkan. Bagi rujukan umum, anda boleh melawati laman web awam di <a href="https://www.ppjub.com.my" target="_blank" class="underline">www.ppjub.com.my</a>. Untuk akses aplikasi Sistem, sila gunakan portal <a href="https://ppjub.my" target="_blank" class="underline">ppjub.my</a>.</p>
                <p class="text-gray-600 text-xs">Kali terakhir dikemas kini: ${new Date().toLocaleDateString('ms-MY')}</p>
            `,
            privasi: `
                <h4 class="font-semibold mb-2">Dasar Privasi – Sistem Laravel PPJUB</h4>
                <p class="mb-3">Dasar Privasi ini menerangkan bagaimana Pertubuhan Pengurusan Jenazah Ummah Bintulu ("PPJUB", "kami") mengumpul, menggunakan, mendedahkan, menyimpan, melindungi dan melupuskan data peribadi yang diproses melalui Sistem aplikasi dalaman berasaskan Laravel ("Sistem"). Dasar ini direka bentuk agar sejajar dengan undang‑undang Malaysia yang terpakai dan amalan terbaik keselamatan maklumat. Dengan mengakses atau menggunakan Sistem, anda ("pengguna") mengakui bahawa anda telah membaca, memahami dan bersetuju dengan terma Dasar Privasi ini.</p>

                <h5 class="font-semibold mt-3 mb-1">1) Pengenalan & Skop</h5>
                <p class="mb-2">Dasar ini terpakai kepada semua pengguna yang diberi kuasa menggunakan Sistem termasuk pegawai, sukarelawan, kontraktor, serta wakil pihak ketiga yang terlibat secara sah dalam operasi PPJUB. Ia meliputi pemprosesan data yang berlaku apabila pengguna log masuk, memasukkan data rekod, memuat naik dokumen, mengurus konfigurasi, menggunakan modul komunikasi, atau berinteraksi dengan integrasi pihak ketiga yang disokong oleh Sistem.</p>

                <h5 class="font-semibold mt-3 mb-1">2) Prinsip Perlindungan Data</h5>
                <p class="mb-2">Kami mengamalkan prinsip keperluan munasabah, ketelusan dan akauntabiliti. Pemprosesan dilakukan secara adil dan sah, terhad kepada tujuan yang dibenarkan, tepat dan terkini setakat yang munasabah, disimpan hanya selama perlu, dan dilindungi dengan kawalan teknikal serta organisasi yang sesuai.</p>

                <h5 class="font-semibold mt-3 mb-1">3) Jenis Data Yang Dikumpul</h5>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li><strong>Data Pengenalan:</strong> Nama, nombor pengenalan, peranan pengguna, emel kerja, nombor telefon tugas, organisasi/unit.</li>
                    <li><strong>Data Operasi:</strong> Rekod pengurusan, butiran waris, log aktiviti, status tugas, catatan audit, konfigurasi modul.</li>
                    <li><strong>Data Teknikal:</strong> Alamat IP, jenis pelayar, cap masa, ID sesi, token akses, maklumat ralat sistem, metrik prestasi.</li>
                    <li><strong>Dokumen & Lampiran:</strong> Fail yang dimuat naik seperti PDF, imej, atau bahan sokongan yang diperlukan untuk operasi.</li>
                    <li><strong>Komunikasi:</strong> Kandungan maklum balas, notifikasi, mesej sistem dan interaksi bantuan.</li>
                </ul>

                <h5 class="font-semibold mt-3 mb-1">4) Asas & Tujuan Pemprosesan</h5>
                <p class="mb-2">Kami memproses data berdasarkan asas yang sah seperti pelaksanaan fungsi sah PPJUB, pematuhan undang‑undang, kepentingan yang sah untuk operasi, serta persetujuan khusus apabila diperlukan. Tujuan utama termasuk:</p>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>Menjalankan dan memperbaiki operasi pentadbiran PPJUB.</li>
                    <li>Pengurusan rekod, pengesahan dan penyelarasan data.</li>
                    <li>Keselamatan, audit, pencegahan penipuan dan pematuhan.</li>
                    <li>Penyelenggaraan, pemantauan prestasi dan sokongan teknikal.</li>
                    <li>Komunikasi operasi, notifikasi dan respons kepada pertanyaan.</li>
                </ul>

                <h5 class="font-semibold mt-3 mb-1">5) Sumber Pengumpulan</h5>
                <p class="mb-2">Data diperoleh melalui input pengguna yang diberi kuasa, integrasi perkhidmatan pihak ketiga yang diluluskan, migrasi data yang sah, serta proses log automatik Sistem. Kami tidak mendapatkan data daripada sumber yang tidak dibenarkan.</p>

                <h5 class="font-semibold mt-3 mb-1">6) Ketepatan & Kemas Kini</h5>
                <p class="mb-2">Pengguna hendaklah memastikan data yang dimasukkan adalah tepat dan terkini setakat yang munasabah. Mekanisme pembetulan disediakan melalui modul atau permintaan rasmi kepada pentadbir sistem.</p>

                <h5 class="font-semibold mt-3 mb-1">7) Penyimpanan & Tempoh Retensi</h5>
                <p class="mb-2">Data disimpan hanya selama yang perlu bagi memenuhi tujuan yang dinyatakan atau selama yang diperlukan oleh undang‑undang. Tempoh retensi berbeza mengikut kategori data; log teknikal lazimnya disimpan lebih singkat berbanding rekod operasi yang memerlukan rujukan.</p>

                <h5 class="font-semibold mt-3 mb-1">8) Keselamatan Maklumat</h5>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>Kawalan akses berasaskan peranan dan keperluan tugas.</li>
                    <li>Pengurusan token/pengesahan serta amalan kata laluan yang baik.</li>
                    <li>Pengasingan persekitaran, semakan log dan pemantauan insiden.</li>
                    <li>Sandaran berkala dan pemulihan bencana setakat munasabah.</li>
                    <li>Garis panduan pengendalian data sensitif dan latihan kesedaran.</li>
                </ul>
                <p class="mb-2">Walaupun langkah-langkah dinyatakan dilaksanakan, tiada jaminan keselamatan mutlak dapat diberi terhadap ancaman siber yang sentiasa berevolusi. Sebarang insiden akan ditangani mengikut prosedur respons insiden PPJUB.</p>

                <h5 class="font-semibold mt-3 mb-1">9) Pendedahan kepada Pihak Ketiga</h5>
                <p class="mb-2">Kami boleh mendedahkan data kepada pihak ketiga yang sah (contohnya penyedia hosting, perkhidmatan emel, pembekal analitik, atau agensi penguatkuasaan) setakat yang perlu untuk tujuan operasi yang dibenarkan atau pematuhan undang‑undang. Semua pihak hendaklah mematuhi obligasi kerahsiaan dan keselamatan yang munasabah.</p>

                <h5 class="font-semibold mt-3 mb-1">10) Pemindahan Rentas Sempadan</h5>
                <p class="mb-2">Jika data diproses atau disimpan di perkhidmatan awan/pusat data di luar Malaysia, kami akan mengambil langkah munasabah untuk memastikan tahap perlindungan yang setara melalui terma kontrak dan kawalan keselamatan yang difikirkan sesuai.</p>

                <h5 class="font-semibold mt-3 mb-1">11) Hak Subjek Data</h5>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>Hak untuk memohon akses kepada data peribadi yang dipegang.</li>
                    <li>Hak pembetulan jika data tidak tepat atau tidak lengkap.</li>
                    <li>Hak bantahan/pembatasan pemprosesan atas alasan yang munasabah.</li>
                    <li>Hak pemadaman tertakluk kepada kewajipan undang‑undang dan operasi.</li>
                    <li>Hak menarik balik persetujuan, jika pemprosesan berasaskan persetujuan.</li>
                </ul>
                <p class="mb-2">Permintaan hak hendaklah dibuat melalui saluran rasmi pentadbir sistem dan mungkin memerlukan pengesahan identiti. Tindak balas akan diberikan dalam tempoh munasabah.</p>

                <h5 class="font-semibold mt-3 mb-1">12) Kuki, Log & Telemetri</h5>
                <p class="mb-2">Sistem mungkin menggunakan kuki sesi dan teknologi serupa untuk memastikan keselamatan log masuk, mengekalkan sesi, serta meningkatkan pengalaman pengguna. Log pelayan dan telemetri prestasi digunakan untuk penyelesaian masalah dan penambahbaikan.</p>

                <h5 class="font-semibold mt-3 mb-1">13) Komunikasi & Notifikasi</h5>
                <p class="mb-2">Kami boleh menghantar emel atau notifikasi sistem berkaitan pengesahan, amaran keselamatan, perubahan konfigurasi, atau makluman operasi. Komunikasi ini dianggap perlu untuk menjalankan fungsi Sistem.</p>

                <h5 class="font-semibold mt-3 mb-1">14) Kanak‑kanak</h5>
                <p class="mb-2">Sistem ditujukan kepada kakitangan/pegawai/sukarelawan yang diberi kuasa. Ia tidak bertujuan untuk digunakan oleh kanak‑kanak. Jika secara tidak sengaja kami menerima data kanak‑kanak tanpa kebenaran sewajarnya, langkah pengehadkan atau pemadaman akan dilaksanakan setakat munasabah.</p>

                <h5 class="font-semibold mt-3 mb-1">15) Tanggungjawab Pengguna</h5>
                <p class="mb-2">Pengguna hendaklah melindungi kelayakan log masuk, memastikan peranti selamat, tidak berkongsi data di luar tujuan kerja, dan mematuhi garis panduan PPJUB. Pelanggaran boleh menyebabkan tindakan pentadbiran atau perundangan.</p>

                <h5 class="font-semibold mt-3 mb-1">16) Perubahan Dasar</h5>
                <p class="mb-2">Dasar ini boleh dikemas kini dari semasa ke semasa. Versi terbaharu akan dipaparkan di dalam Sistem dan berkuat kuasa serta‑merta melainkan dinyatakan sebaliknya. Penggunaan berterusan selepas perubahan menandakan penerimaan anda terhadap kemas kini tersebut.</p>

                <h5 class="font-semibold mt-3 mb-1">17) Cara Menghubungi Kami</h5>
                <p class="mb-3">Sebarang pertanyaan, permintaan akses/pembetulan data, atau aduan privasi boleh dikemukakan melalui sekretariat PPJUB melalui saluran rasmi. Untuk rujukan umum, sila lawati laman web awam di <a href="https://www.ppjub.com.my" target="_blank" class="underline">www.ppjub.com.my</a>. Untuk akses aplikasi Sistem, gunakan portal <a href="https://ppjub.my" target="_blank" class="underline">ppjub.my</a>.</p>
                <p class="text-gray-600 text-xs">Kali terakhir dikemas kini: ${new Date().toLocaleDateString('ms-MY')}</p>
            `,
            terma: `
                <h4 class="font-semibold mb-2">Terma Penggunaan – Sistem Laravel PPJUB</h4>
                <p class="mb-3">Terma Penggunaan ini mengawal akses dan penggunaan Sistem aplikasi dalaman PPJUB yang berasaskan Laravel ("Sistem"). Dengan log masuk, mengakses atau menggunakan Sistem, anda bersetuju untuk terikat dengan terma ini. Jika anda tidak bersetuju, sila hentikan penggunaan serta‑merta.</p>

                <h5 class="font-semibold mt-3 mb-1">1) Definisi</h5>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li><strong>PPJUB</strong>: Pertubuhan Pengurusan Jenazah Ummah Bintulu.</li>
                    <li><strong>Pengguna</strong>: Individu yang diberi kebenaran oleh PPJUB untuk mengakses Sistem.</li>
                    <li><strong>Sistem</strong>: Aplikasi dalaman berasaskan Laravel termasuk modul, API dan integrasi.</li>
                </ul>

                <h5 class="font-semibold mt-3 mb-1">2) Syarat Akses</h5>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>Akses diberikan berdasarkan peranan dan keperluan tugas.</li>
                    <li>PPJUB boleh menggantung atau menamatkan akses pada bila‑bila masa atas sebab keselamatan atau pelanggaran terma.</li>
                    <li>Pengguna hendaklah menggunakan kelayakan log masuk sendiri dan merahsiakannya.</li>
                </ul>

                <h5 class="font-semibold mt-3 mb-1">3) Kelakuan Pengguna</h5>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>Dilarang memuat naik kandungan berniat jahat, menyalahi undang‑undang atau melanggar hak pihak lain.</li>
                    <li>Dilarang mencuba mendapatkan akses tanpa kebenaran, memintas kawalan keselamatan, atau mengeksploitasi kelemahan.</li>
                    <li>Sebarang automasi (bot/scraping) memerlukan kebenaran bertulis daripada PPJUB.</li>
                    <li>Pengguna bertanggungjawab terhadap semua aktiviti yang berlaku di bawah akaun mereka.</li>
                </ul>

                <h5 class="font-semibold mt-3 mb-1">4) Data & Kerahsiaan</h5>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>Data dalam Sistem adalah sulit dan digunakan untuk tujuan operasi yang sah.</li>
                    <li>Dilarang menyalin, mengeksport atau berkongsi data tanpa kebenaran.</li>
                    <li>Pemprosesan data tertakluk kepada Dasar Privasi PPJUB yang berkuat kuasa.</li>
                </ul>

                <h5 class="font-semibold mt-3 mb-1">5) Harta Intelek</h5>
                <p class="mb-2">Segala kandungan, reka bentuk, pangkalan data, dokumentasi dan kod sumber adalah hak milik PPJUB kecuali dinyatakan sebaliknya. Tiada lesen diberikan melainkan dinyatakan secara bertulis.</p>

                <h5 class="font-semibold mt-3 mb-1">6) Integrasi & Perkhidmatan Pihak Ketiga</h5>
                <p class="mb-2">Sistem mungkin menggunakan perkhidmatan luaran (contoh: SMTP, storan, peta, atau API kerajaan). PPJUB tidak bertanggungjawab ke atas polisi, ketersediaan, atau perubahan yang dibuat oleh pembekal tersebut.</p>

                <h5 class="font-semibold mt-3 mb-1">7) Had Tanggungjawab</h5>
                <p class="mb-2">Sistem disediakan "seadanya" tanpa jaminan tersurat atau tersirat. PPJUB tidak bertanggungjawab atas kerugian langsung/tidak langsung yang timbul daripada penggunaan Sistem termasuk kehilangan data, gangguan, atau salah tafsir maklumat.</p>

                <h5 class="font-semibold mt-3 mb-1">8) Keselamatan</h5>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li>Pengguna wajib mematuhi garis panduan keselamatan PPJUB.</li>
                    <li>Lapor segera sebarang insiden keselamatan, pendedahan kelayakan atau aktiviti mencurigakan.</li>
                </ul>

                <h5 class="font-semibold mt-3 mb-1">9) Pematuhan Undang‑undang</h5>
                <p class="mb-2">Pengguna hendaklah mematuhi semua undang‑undang dan peraturan berkuat kuasa semasa menggunakan Sistem. Permintaan pihak berkuasa akan dipatuhi mengikut undang‑undang yang terpakai.</p>

                <h5 class="font-semibold mt-3 mb-1">10) Penggantungan & Penamatan</h5>
                <p class="mb-2">PPJUB boleh menggantung atau menamatkan akses pengguna tanpa notis terlebih dahulu jika disyaki berlaku pelanggaran terma, ancaman keselamatan, atau keperluan operasi.</p>

                <h5 class="font-semibold mt-3 mb-1">11) Perubahan Terma</h5>
                <p class="mb-2">PPJUB boleh mengemas kini Terma Penggunaan ini pada bila‑bila masa. Versi terbaharu dalam Sistem akan menggantikan versi sebelumnya. Penggunaan berterusan dianggap sebagai penerimaan anda terhadap sebarang perubahan.</p>

                <h5 class="font-semibold mt-3 mb-1">12) Hubungi Kami</h5>
                <p class="mb-3">Untuk pertanyaan berkaitan Terma Penggunaan, sila hubungi sekretariat PPJUB melalui saluran rasmi. Laman awam: <a href="https://www.ppjub.com.my" target="_blank" class="underline">www.ppjub.com.my</a> | Portal Sistem: <a href="https://ppjub.my" target="_blank" class="underline">ppjub.my</a>.</p>
                <p class="text-gray-600 text-xs">Kali terakhir dikemas kini: ${new Date().toLocaleDateString('ms-MY')}</p>
            `,
            peta: `
                <h3 class="text-lg font-semibold mb-4">Peta Laman – Sistem Pengurusan Kubur</h3>
                <div class="space-y-6">
                  <section class="rounded-md bg-gray-50 border border-gray-100 p-4">
                    <h5 class="font-semibold text-gray-800 mb-2">Papan Pemuka</h5>
                    <ul class="list-disc pl-7 space-y-2 leading-relaxed text-gray-700">
                      <li><a href="/overview" class="underline">Papan Pemuka</a> — ringkasan status & aktiviti.</li>
                    </ul>
                  </section>
                  <section class="rounded-md bg-gray-50 border border-gray-100 p-4">
                    <h5 class="font-semibold text-gray-800 mb-2">Pengurusan</h5>
                    <ul class="list-disc pl-7 space-y-2 leading-relaxed text-gray-700">
                      <li><a href="/kematian" class="underline">Daftar Kematian</a></li>
                      <li><a href="/ppjub" class="underline">Ahli PPJUB</a></li>
                    </ul>
                  </section>
                  <section class="rounded-md bg-gray-50 border border-gray-100 p-4">
                    <h5 class="font-semibold text-gray-800 mb-2">Pentadbiran Sistem</h5>
                    <ul class="list-disc pl-7 space-y-2 leading-relaxed text-gray-700">
                      <li><a href="/tetapan" class="underline">Tetapan Umum</a></li>
                      <li><a href="/integrations" class="underline">Integrasi</a> — API, token, sambungan luaran.</li>
                      <li><a href="/sanctum-tokens" class="underline">Token Akses (Sanctum)</a></li>
                      <li><a href="/users" class="underline">Pengguna</a> · <a href="/roles" class="underline">Peranan</a> · <a href="/permissions" class="underline">Kebenaran</a></li>
                    </ul>
                  </section>
                  <section class="rounded-md bg-gray-50 border border-gray-100 p-4">
                    <h5 class="font-semibold text-gray-800 mb-2">Bantuan & Rujukan</h5>
                    <ul class="list-disc pl-7 space-y-2 leading-relaxed text-gray-700">
                      <li><a href="/user-guide" class="underline">Panduan Pengguna</a></li>
                      <li><a href="/release-notes" class="underline">Nota Keluaran</a></li>
                      <li><a href="/health" class="underline">Semakan Kesihatan Sistem</a></li>
                      <li><a href="#" class="footer-modal-link underline" data-modal-key="penafian" data-modal-title="Penafian">Penafian</a> · <a href="#" class="footer-modal-link underline" data-modal-key="privasi" data-modal-title="Privasi">Dasar Privasi</a> · <a href="#" class="footer-modal-link underline" data-modal-key="terma" data-modal-title="Terma Penggunaan">Terma Penggunaan</a></li>
                    </ul>
                  </section>
                </div>
            `
        };

        function openModal(title, key){
            titleEl.textContent = title || 'Maklumat';
            contentEl.innerHTML = CONTENT_MAP[key] || 'Kandungan ringkas.';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(){
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
        document.querySelectorAll('.footer-modal-link').forEach(a => {
            a.addEventListener('click', function(e){
                e.preventDefault();
                openModal(this.dataset.modalTitle, this.dataset.modalKey);
            });
        });
        [closeBtn, backdrop].forEach(el => el && el.addEventListener('click', closeModal));
    });
    </script>
</footer> 