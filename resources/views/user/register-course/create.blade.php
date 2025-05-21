@extends('layouts.app')

@section('title', 'Daftar Kursus Renang')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Daftar Kursus Renang</h1>
        <p class="text-gray-600">Silakan isi formulir di bawah ini untuk mendaftar kursus renang</p>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($courses->isEmpty())
        <div class="bg-yellow-100 p-6 rounded-lg text-center">
            <p>Tidak ada kursus renang yang tersedia saat ini.</p>
            <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline mt-2 inline-block">
                Kembali ke Dashboard
            </a>
        </div>
    @else
        <div class="bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('register-course.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="swimming_course_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Pilih Kursus Renang
                    </label>
                    <select id="swimming_course_id" name="swimming_course_id" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">-- Pilih Kursus --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('swimming_course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }} - {{ $course->level }} (Rp{{ number_format($course->price, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="courseDetails" class="mb-4 p-4 bg-gray-50 rounded-md hidden">
                    <h3 class="font-semibold mb-2">Detail Kursus</h3>
                    <div id="courseDescription" class="text-sm text-gray-600 mb-2"></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Durasi:</span>
                            <span id="courseDuration" class="text-sm text-gray-600"></span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Sesi per Minggu:</span>
                            <span id="courseSessions" class="text-sm text-gray-600"></span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Instruktur:</span>
                            <span id="courseInstructor" class="text-sm text-gray-600"></span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Harga:</span>
                            <span id="coursePrice" class="text-sm text-gray-600"></span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Mulai
                    </label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" 
                           min="{{ date('Y-m-d') }}" required
                           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                        Catatan Tambahan
                    </label>
                    <textarea id="notes" name="notes" rows="3" 
                              class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Tambahkan informasi penting, misalnya riwayat medis, alergi, atau kondisi khusus lainnya.</p>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('my-registrations') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        Kembali ke daftar pendaftaran
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                        Daftar Kursus
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Array to store course data
    const courses = @json($courses);
    
    // Get elements
    const courseSelect = document.getElementById('swimming_course_id');
    const courseDetails = document.getElementById('courseDetails');
    const courseDescription = document.getElementById('courseDescription');
    const courseDuration = document.getElementById('courseDuration');
    const courseSessions = document.getElementById('courseSessions');
    const courseInstructor = document.getElementById('courseInstructor');
    const coursePrice = document.getElementById('coursePrice');
    
    // Add event listener to course select
    courseSelect.addEventListener('change', function() {
        const courseId = this.value;
        
        if (!courseId) {
            courseDetails.classList.add('hidden');
            return;
        }
        
        // Find selected course data
        const course = courses.find(c => c.id == courseId);
        
        if (course) {
            // Update course details
            courseDescription.textContent = course.description;
            courseDuration.textContent = `${course.duration} minggu`;
            courseSessions.textContent = `${course.sessions_per_week}x per minggu`;
            courseInstructor.textContent = course.instructor || 'Belum ditentukan';
            coursePrice.textContent = `Rp${new Intl.NumberFormat('id-ID').format(course.price)}`;
            
            // Show course details
            courseDetails.classList.remove('hidden');
        }
    });
    
    // Trigger change event if a course is already selected (e.g., after validation error)
    if (courseSelect.value) {
        courseSelect.dispatchEvent(new Event('change'));
    }
</script>
@endpush
@endsection