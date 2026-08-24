@extends('dashboardLayouts.base')

@section('content')
<div class="template" id="templateContainer">
    <div class="template-content">
        <!-- Save Template Form -->
        <form action="{{ route('storeTemp') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Template Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="summernote" class="form-label">Template Content</label>
                <textarea id="summernote" name="html_content"></textarea>
            </div>

            <button type="submit" class="btn btn-success mt-3">Save</button>
        </form>
    </div>

    <div class="d-flex justify-content-end export-btn mt-3">
        <button id="exportPdf" class="btn btn-primary">
            <i class="fas fa-file-pdf me-2"></i>Export as PDF (A4)
        </button>
    </div>
</div>

<h1>Templates</h1>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($templates as $template)
                <tr>
                    <td>{{ $template->id }}</td>
                    <td>{{ $template->name }}</td>
                   <td>
                        <a href="{{ route('viewTemp', ['id' => $template->id]) }}">Work Here</a>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>



<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
$(function () {
    // ✅ Summernote init with auto textarea sync
    $('#summernote').summernote({
        height: 300,
        placeholder: 'Start typing your content here...',
        callbacks: {
            onChange: function(contents) {
                $('#summernote').val(contents); // keep <textarea> updated
            }
        }
    });

    // ✅ Export PDF (short & clean)
    $('#exportPdf').on('click', function (e) {
        e.preventDefault();

        const btn = $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Generating...');

        const content = $('#summernote').val(); // get HTML from textarea
        const tempDiv = $('<div class="a4-preview"></div>').html(content).appendTo('body');

        html2canvas(tempDiv[0], { scale: 2, useCORS: true }).then(canvas => {
            tempDiv.remove();

            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('p', 'mm', 'a4');
            pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, 0, 210, 297);
            pdf.save('template-export-a4.pdf');
        }).catch(err => alert('Error generating PDF'))
          .always(() => btn.prop('disabled', false).html('<i class="fas fa-file-pdf me-2"></i>Export as PDF (A4)'));
    });
});
</script>

<style>
    body { background-color: #f8f9fa; padding: 20px; }
    .template { max-width: 1200px; margin: auto; background: #fff; padding: 20px;
                border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .a4-preview { width: 210mm; min-height: 297mm; padding: 20mm; background: #fff; }
</style>
@endsection
