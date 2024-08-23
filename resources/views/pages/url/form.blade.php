@extends('layouts.app')

@section('title', 'Submit URLs')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Submit URLs</h3>
        </div>
        <div class="card-body">

            <form method="POST" action="{{ route('url.store') }}" x-data="urlForm()" @submit.prevent="submitForm">
                @csrf

                <div class="mb-3">
                    <label for="urls" class="form-label">Enter URLs (each on a new line)</label>
                    <textarea name="urls" id="urls" class="form-control" rows="20" required x-model="urls"></textarea>
                    <div class="form-text" x-text="`${countUrls()} URLs entered`"></div>
                </div>

                <button type="submit" class="btn btn-primary" :disabled="urls.trim() === ''">Submit</button>
            </form>
        </div>
    </div>

    <script>
        function urlForm() {
            return {
                urls: '',
                countUrls() {
                    return this.urls.trim() ? this.urls.trim().split('\n').filter(url => url).length : 0;
                },

                submitForm() {
                    if (this.countUrls() > 0) {
                        this.$el.submit();
                    } else {
                        alert('Please enter at least one URL.');
                    }
                }
            }
        }
    </script>
@endsection
