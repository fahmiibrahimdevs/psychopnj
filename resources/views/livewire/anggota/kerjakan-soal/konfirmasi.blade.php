<div>
    <section class="section custom-section">
        <div class="tw-border-l-4 tw-border-blue-500 tw-bg-blue-100 tw-p-4 tw-mb-4 tw-rounded-md">
            <h4 class="tw-text-blue-700 tw-font-bold tw-mb-2 tw-text-base">
                <i class="fas fa-info-circle tw-mr-1"></i>
                Peraturan Mengerjakan Soal
            </h4>
            <ul class="tw-list-disc tw-ml-8 tw-text-blue-800">
                <li>Kerjakan soal dengan jujur dan mandiri</li>
                <li>Pastikan jawaban sudah yakin sebelum menyelesaikan</li>
                <li>Soal yang ditandai "Ragu" harus dihilangkan sebelum menyelesaikan</li>
                <li>Jangan meninggalkan halaman saat mengerjakan soal</li>
            </ul>
        </div>

        <div class="section-body mt-3">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-primary">
                        <h3>Konfirmasi Data</h3>
                        <div class="card-body">
                            <table>
                                <tr>
                                    <td class="tw-border tw-tracking-wide" width="30%">Nama</td>
                                    <td class="tw-border tw-tracking-wide">{{ Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <td class="tw-border tw-tracking-wide" width="30%">Program</td>
                                    <td class="tw-border tw-tracking-wide">{{ $pertemuan->program->nama_program ?? "-" }}</td>
                                </tr>
                                <tr>
                                    <td class="tw-border tw-tracking-wide" width="30%">Pertemuan</td>
                                    <td class="tw-border tw-tracking-wide">Pertemuan {{ $pertemuan->pertemuan_ke }}</td>
                                </tr>
                                <tr>
                                    <td class="tw-border tw-tracking-wide" width="30%">Judul</td>
                                    <td class="tw-border tw-tracking-wide">{{ $pertemuan->judul_pertemuan }}</td>
                                </tr>
                                <tr>
                                    <td class="tw-border tw-tracking-wide" width="30%">Pemateri</td>
                                    <td class="tw-border tw-tracking-wide">{{ $pertemuan->nama_pemateri ?? "-" }}</td>
                                </tr>
                                <tr>
                                    <td class="tw-border tw-tracking-wide" width="30%">Jumlah Soal</td>
                                    <td class="tw-border tw-tracking-wide">{{ $totalSoal }} Soal</td>
                                </tr>
                                <tr>
                                    <td class="tw-border tw-tracking-wide" width="30%">Tanggal</td>
                                    <td class="tw-border tw-tracking-wide">{{ $pertemuan->tanggal ? \Carbon\Carbon::parse($pertemuan->tanggal)->format("d M Y") : "-" }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body px-4 tw-text-center">
                            <div class="tw-mb-4">
                                <i class="fas fa-clipboard-check tw-text-5xl tw-text-blue-500 tw-mb-3"></i>
                                <p class="tw-text-gray-600 tw-text-sm">Klik tombol di bawah untuk mulai mengerjakan soal</p>
                            </div>
                            <button wire:click.prevent="mulaiKerjakan()" class="btn btn-primary form-control tw-rounded-full">
                                <i class="fas fa-edit tw-text-sm tw-mr-1"></i>
                                MULAI KERJAKAN
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
