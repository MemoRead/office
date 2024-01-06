    <!-- Modal Invitation-->
    <div class="modal modal-lg fade" id="modalDialogInvitation" tabindex="-1" aria-labelledby="modalDialogInvitationLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Outgoing Mail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show col-lg-8" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="modal-body">
                    <form method="POST" action="/dashboard/mails/outgoing-mails">
                        @csrf
                        <div class="card col-lg-12">
                            <div class="card-body">
                                <h5 class="card-title">Invitation Letter</h5>
                                <div class="row mb-3">
                                    <label for="type" class="col-sm-2 col-form-label">Letter Type</label>
                                    <div class="col-sm-10">
                                        <input type="hidden" class="form-control @error('type') is-invalid @enderror"
                                            value="Undangan" id="type" name="type">
                                        <input type="text" class="form-control @error('type') is-invalid @enderror"
                                            value="Undangan" id="" name="" disabled required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="number" class="col-sm-2 col-form-label">Mails Number</label>
                                    <div class="col-sm-10">
                                        <input id="number" name="number" type="text"
                                            class="form-control @error('number') is-invalid @enderror"
                                            value="{{ old('number') }}" required>
                                    </div>
                                    @error('number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="row mb-3">
                                    <label for="city" class="col-sm-2 col-form-label">City</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                            value="{{ old('city') }}" id="city" name="city" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="date" class="col-sm-2 col-form-label">Date</label>
                                    <div class="col-sm-10">
                                        <input type="hidden" class="form-control @error('type') is-invalid @enderror"
                                            value="{{ $now }}" id="date" name="date">
                                        <input type="date" value="{{ $now }}"
                                            class="form-control @error('date') is-invalid @enderror"
                                            value="{{ old('date') }}" id="" name="" required disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="subject" class="col-sm-2 col-form-label">Subject</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control @error('subject') is-invalid @enderror"
                                            value="{{ old('subject') }}" id="subject" name="subject" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="attachment" class="col-sm-2 col-form-label">Attachment</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control @error('attachment') is-invalid @enderror"
                                            value="{{ old('attachment') }}" id="attachment" name="attachment" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="receiver" class="col-sm-2 col-form-label">Receiver</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control @error('receiver') is-invalid @enderror"
                                            value="{{ old('receiver') }}" id="receiver" name="receiver" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="re_location" class="col-sm-2 col-form-label">Receiver Location</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control @error('re_location') is-invalid @enderror"
                                            value="{{ old('re_location') }}" id="re_location" name="re_location"
                                            required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Concerned employee (PIC)</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" aria-label="member" name="member_id">
                                            <option value="" selected>Choose PIC</option>
                                            @foreach ($members as $member)
                                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card col-lg-12">
                            <div class="card-body">
                                <h5 class="card-title">Contents of the letter</h5>
                                <input id="content" type="hidden" name="content">
                                <trix-editor input="content"></trix-editor>
                            </div>
                        </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil elemen input nomor surat
            const numberInput = document.getElementById('number');

            // Jika elemen input nomor surat tidak kosong, keluar dari fungsi
            if (numberInput.value.trim() !== '') {
                return;
            }

            // Fetch nomor surat terakhir dari server
            fetch('/documents/get-last-number')
                .then(response => response.json())
                .then(data => {
                    // Increment nomor surat
                    const nextNumber = (data.number) ? data.number + 1 : 1;

                    // Format nomor surat dengan menambahkan angka 00 di depan
                    const formattedNumber = String(nextNumber).padStart(3, '0');

                    // Set nilai nomor surat pada input
                    numberInput.value = formattedNumber;
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
