@extends('layouts.app')

@section('title', 'レシート撮影')

@section('content')
<div class="container">
  <h2 class="mb-3">📸 レシート撮影</h2>

  <div class="mb-3">
    <video id="camera-preview" width="100%" autoplay muted></video>
  </div>

  <div class="text-center mb-3">
    <button id="capture-btn" class="btn btn-primary">撮影する</button>
  </div>

  <canvas id="snapshot" style="display:none;"></canvas>

  <form id="capture-form" method="POST" action="{{ route('receipt.store') }}">
    @csrf
    <input type="hidden" name="image_data" id="image-data">
    <div class="text-center">
      <button type="submit" class="btn btn-success" style="display:none;" id="submit-btn">この画像で進む</button>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script>
  const video = document.getElementById('camera-preview');
  const canvas = document.getElementById('snapshot');
  const captureBtn = document.getElementById('capture-btn');
  const imageData = document.getElementById('image-data');
  const submitBtn = document.getElementById('submit-btn');

  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream;
    });

  captureBtn.addEventListener('click', () => {
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataURL = canvas.toDataURL('image/png');
    imageData.value = dataURL;
    submitBtn.style.display = 'inline-block';
  });
</script>
@endsection