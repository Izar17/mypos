@extends('layout.dashboard')

@section('layout.dashboard.body')
    <div class="h-full flex-auto flex flex-col">
        @include(Hook::filter('ns-dashboard-header', '../common/dashboard-header'))
        <div class="px-4 flex-auto flex flex-col" id="dashboard-content">
            @include('common.dashboard.title')
            <div class="">
                <form method="POST" action="{{ route('ns.dashboard.products.mass-upload-product') }}"
                    enctype="multipart/form-data">
                    @method('POST')
                    @csrf
                    <div class="flex justify-center mt-8">
                        <div class="max-w-2xl rounded-lg shadow-xl bg-gray-50">
                            <div class="m-4">
                                <label class="inline-block mb-2 text-gray-500">File Upload</label>
                                <div class="flex items-center justify-center w-full">
                                    <label
                                        class="flex flex-col w-full h-32 border-4 border-blue-200 border-dashed hover:bg-gray-100 hover:border-gray-300">
                                        <div class="flex flex-col items-center justify-center pt-7">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-8 h-8 text-gray-400 group-hover:text-gray-600" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <p class="pt-1 text-sm tracking-wider text-gray-400 group-hover:text-gray-600">
                                                Attach a file</p>
                                        </div>
                                        <input type="file" name='file' class="opacity-0" />
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-center p-2">
                                <button type="submit"
                                    class="w-full px-4 py-2 text-white bg-blue-500 rounded shadow-xl">Upload</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

        </div>
        <!-- Toast Notification Success-->
        @if ($message = Session::get('success'))
            <div class="flex items-center bg-green-500 border-l-4 border-green-700 py-2 px-3 shadow-md mb-2">
                <!-- icons -->
                <div class="text-green-500 rounded-full bg-white mr-3">
                    <svg width="1.8em" height="1.8em" viewBox="0 0 16 16" class="bi bi-check" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.236.236 0 0 1 .02-.022z" />
                    </svg>
                </div>
                <!-- message -->
                <div class="text-white max-w-xs ">
                    {{ $message }} 
                </div>
            </div>
        @endif
        @if (count($errors) > 0)
            <!-- Toast Notification Danger -->
            <div class="flex items-center bg-red-500 border-l-4 border-red-700 py-2 px-3 shadow-md mb-2">
                <!-- icons -->
                <div class="text-red-500 rounded-full bg-white mr-3">
                    <svg width="1.8em" height="1.8em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
                        <path fill-rule="evenodd"
                            d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
                    </svg>
                </div>
                <!-- message -->
                @foreach ($errors->all() as $error)
                    <div class="text-white max-w-xs ">
                        {{ $error }}
                    </div>
                @endforeach
        @endif
    </div>
@endsection
