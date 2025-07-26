<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\SwimmingCourse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk hashing password
use Illuminate\Support\Facades\DB;   // Untuk string manipulation (email, password generation)
use Illuminate\Support\Facades\Hash; // Untuk transaksi database
use Illuminate\Support\Str;          // Untuk Rule::unique jika masih ada
use Illuminate\Validation\Rule;

class GuruMuridController extends Controller
{
    /**
     * Display a listing of the murid.
     */
    public function index()
    {
        $guruId = Auth::id();
        $murids = User::whereHas('gurus', function ($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })
            ->where('role', 'murid')
            ->with(['swimmingCourse', 'registrations' => function ($query) {
                $query->where('status', 'approved')->latest('start_date');
            }])
            ->paginate(10);

        $availableCourses = SwimmingCourse::where('is_active', true)->get();

        return view('guru.murid.index', compact('murids', 'availableCourses'));
    }

    /**
     * Show the form for adding an EXISTING murid to the guru's guidance.
     * This method remains unchanged.
     */
    public function create()
    {
        $guruUser         = Auth::user();
        $existingMuridIds = $guruUser->murids()->pluck('users.id')->toArray();

        $murids = User::where('role', 'murid')
            ->where('status', 'active')
            ->whereNotIn('id', $existingMuridIds)
            ->get();

        return view('guru.murid.create', compact('murids'));
    }

    /**
     * Store an EXISTING murid to the guru's guidance.
     * This method remains unchanged.
     */
    public function store(Request $request)
    {
        $request->validate([
            'murid_id' => [
                'required',
                'exists:users,id,role,murid,status,active',
                Rule::unique('guru_murid')->where(function ($query) {
                    return $query->where('guru_id', Auth::id());
                }),
            ],
        ], [
            'murid_id.unique' => 'Murid ini sudah terdaftar sebagai bimbingan Anda.',
        ]);

        $guruUser = Auth::user();
        $guruUser->murids()->attach($request->murid_id);

        return redirect()->route('guru.murid.index')->with('success', 'Murid berhasil ditambahkan ke daftar bimbingan Anda.');
    }

    /**
     * Show the form for creating a NEW murid account for the guru's guidance.
     * This is the NEW method for the new functionality.
     */
    public function createMuridAccountForm()
    {
        return view('guru.murid.create_account'); // Mengarahkan ke view baru
    }

    /**
     * Store a NEW murid account and attach it to the guru's guidance.
     * This is the NEW method for the new functionality.
     */
    public function storeMuridAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DB::beginTransaction(); // Mulai transaksi database
        try {
            $guruUser = Auth::user();

                                                                          // Bersihkan nama untuk email: lowercase, hapus spasi, ambil 10 karakter pertama
            $cleanName    = Str::slug(substr($request->name, 0, 10), ''); // Menghapus spasi dan karakter khusus, ambil 10
            $uniqueNumber = random_int(100, 999);                         // Generate 3 digit angka unik

            $email       = $cleanName . $uniqueNumber . '@butterfly.com';
            $rawPassword = $cleanName . $uniqueNumber; // Password mentah

            // Pastikan email unik, jika tidak, coba lagi dengan angka unik yang berbeda
            $counter = 0;
            while (User::where('email', $email)->exists() && $counter < 10) { // Batasi percobaan
                $uniqueNumber = random_int(100, 999);
                $email        = $cleanName . $uniqueNumber . '@butterfly.com';
                $rawPassword  = $cleanName . $uniqueNumber;
                $counter++;
            }

            if (User::where('email', $email)->exists()) {
                throw new \Exception('Gagal membuat email unik setelah beberapa percobaan. Silakan coba lagi dengan nama yang berbeda.');
            }

            // Buat akun murid baru
            $murid = User::create([
                'name'     => $request->name,
                'email'    => $email,
                'password' => Hash::make($rawPassword), // Hash password
                'role'     => 'murid',
                'status'   => 'active', // Langsung aktif (approved)
                'is_nacc'  => false,    // Ini adalah akun murid, bukan NACC. Kolom ini tetap ada di DB.
            ]);

            // Assign role 'murid' (penting untuk Spatie)
            $murid->assignRole('murid');

            // Kaitkan murid baru dengan guru yang sedang login
            $guruUser->murids()->attach($murid->id);

            DB::commit(); // Commit transaksi
            return redirect()->route('guru.murid.index')->with('success', 'Akun murid "' . $murid->name . '" berhasil dibuat! Email: ' . $email . ', Password: ' . $rawPassword);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            return redirect()->back()->withInput()->with('error', 'Gagal membuat akun murid: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified murid from guru's guidance.
     * This method remains unchanged.
     */
    public function destroy($id)
    {
        // Pastikan guru yang login memiliki hak untuk melepaskan murid ini
        $guruUser = Auth::user();
        if (! $guruUser->murids()->where('murid_id', $id)->exists()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melepaskan murid ini.');
        }

        DB::beginTransaction();
        try {
            // Lepaskan hubungan guru-murid
            $guruUser->murids()->detach($id);

            // Opsional: Jika Anda ingin menghapus akun murid sepenuhnya jika dia tidak lagi terikat
            // dengan guru manapun dan tidak memiliki data lain (registrasi, absensi, dll)
            // Ini akan memerlukan logika yang lebih kompleks dan mungkin tidak disarankan
            // untuk menjaga integritas data historis. Untuk saat ini, kita hanya detach.

            DB::commit();
            return redirect()->route('guru.murid.index')->with('success', 'Murid berhasil dihapus dari daftar bimbingan Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus murid: ' . $e->getMessage());
        }
    }

    // Metode assignCourse dan extendCourse akan tetap dipertahankan seperti di output.txt Anda
    public function assignCourse(Request $request, User $murid)
    {
        if (Auth::user()->role === 'guru' && ! $murid->gurus->contains(Auth::id())) {
            return response()->json(['error' => 'Anda tidak memiliki akses untuk mengubah murid ini.'], 403);
        }

        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id',
        ]);

        $selectedCourse = SwimmingCourse::find($request->swimming_course_id);

        DB::beginTransaction();
        try {
            // Update data di tabel user
            $murid->swimming_course_id = $selectedCourse->id;
            $murid->course_assigned_at = Carbon::now();

            $murid->jumlah_pertemuan_paket = $selectedCourse->jumlah_pertemuan; // Simpan kuota pertemuan
            $murid->pertemuan_ke           = 0;                                 // Reset hitungan pertemuan
            $murid->save();

            Registration::create([
                'user_id'            => $murid->id,
                'swimming_course_id' => $selectedCourse->id,
                'start_date'         => Carbon::now(),
                'end_date'           => Carbon::now()->addWeeks($selectedCourse->duration), 
                'status'             => 'approved',
                'biaya'              => $selectedCourse->price,
                'guru_id'            => Auth::id(),
            ]);

            DB::commit();
            return response()->json(['success' => 'Kursus berhasil ditugaskan kepada murid!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menugaskan kursus: ' . $e->getMessage()], 500);
        }

    }

    public function extendCourse(Request $request, User $murid)
    {
        if (Auth::user()->role === 'guru' && ! $murid->gurus->contains(Auth::id())) {
            return response()->json(['error' => 'Anda tidak memiliki akses untuk mengubah murid ini.'], 403);
        }

        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id',
        ]);

        $selectedCourse = SwimmingCourse::find($request->swimming_course_id);

        // ========== LOGIKA BARU UNTUK PERPANJANG/PERBARUI KURSUS ==========
        DB::beginTransaction();
        try {
            // Logika ini sama persis dengan assignCourse, efektif me-reset kursus murid
            $murid->swimming_course_id = $selectedCourse->id;
            $murid->course_assigned_at = Carbon::now();

            $murid->jumlah_pertemuan_paket = $selectedCourse->jumlah_pertemuan;
            $murid->pertemuan_ke           = 0; // Reset hitungan pertemuan
            $murid->save();

            Registration::create([
                'user_id'            => $murid->id,
                'swimming_course_id' => $selectedCourse->id,
                'start_date'         => Carbon::now(),
                'end_date'           => Carbon::now()->addWeeks($selectedCourse->duration),
                'status'             => 'approved',
                'biaya'              => $selectedCourse->price,
                'guru_id'            => Auth::id(),
            ]);

            DB::commit();
            return response()->json(['success' => 'Kursus murid berhasil diperbarui/diperpanjang!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal memperbarui kursus: ' . $e->getMessage()], 500);
        }
        // ========== AKHIR PERUBAHAN ==========
    }
}
