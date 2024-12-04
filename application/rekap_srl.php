<style>
    .table-responsive {
        overflow-x: auto;
        /* Hanya aktifkan scroll horizontal jika diperlukan */
    }

    @media (min-width: 768px) {
        .table-responsive {
            overflow-x: visible;
            /* Nonaktifkan scroll horizontal di desktop */
        }
    }
</style>
                <!-- Tabel Rekap SRL -->
                <div class="table-responsive">
                    <h3>Tabel Rekap SRL</h3>
                    <table id="rekap_srl" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Nilai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Data dummy rekap SRL
                            $rekap_data = [
                                ['no' => 1, 'nama' => 'Siswa A', 'kelas' => 'Kelas 1', 'nilai' => 85, 'status' => 'Lulus'],
                                ['no' => 2, 'nama' => 'Siswa B', 'kelas' => 'Kelas 1', 'nilai' => 78, 'status' => 'Lulus'],
                            ];

                            foreach ($rekap_data as $r) {
                                echo "<tr>
                                    <td>{$r['no']}</td>
                                    <td>{$r['nama']}</td>
                                    <td>{$r['kelas']}</td>
                                    <td>{$r['nilai']}</td>
                                    <td>{$r['status']}</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div><!-- /.box-body -->