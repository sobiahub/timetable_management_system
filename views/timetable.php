<?php
use MongoDB\BSON\ObjectId;

include_once "../config/db.php";
include_once "../templates/header.php";

$collection = $db->timetable;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $course = trim($_POST['course']);
    $faculty = trim($_POST['faculty']);
    $timeslot = $_POST['timeslot'];
    $room_no = $_POST['room_no'];

    // Server-side validation
    if (
        !preg_match("/^[A-Za-z\s]+$/", $course) ||
        !preg_match("/^[A-Za-z\s]+$/", $faculty)
    ) {

        $_SESSION['message'] = "Only letters are allowed in Subject and Teacher fields!";

        header("Location: timetable.php");
        exit;
    }

    if (!empty($_POST['id'])) {

        $collection->updateOne(
            ['_id' => new ObjectId($_POST['id'])],
            ['$set' => [
                'course' => $course,
                'faculty' => $faculty,
                'timeslot' => $timeslot,
                'room_no' => $room_no
            ]]
        );

        $_SESSION['message'] = "Record updated successfully!";

    } else {

        $collection->insertOne([
            'course' => $course,
            'faculty' => $faculty,
            'timeslot' => $timeslot,
            'room_no' => $room_no
        ]);

        $_SESSION['message'] = "New record added successfully!";
    }

    header("Location: timetable.php");
    exit;
}

if (isset($_GET['delete_id'])) {

    $collection->deleteOne([
        '_id' => new ObjectId($_GET['delete_id'])
    ]);

    $_SESSION['message'] = "Record deleted successfully!";

    header("Location: timetable.php");
    exit;
}

$timetable_records = $collection->find();
?>

<div class="container page-wrapper">

    <div class="page-header mb-4">
        <h2>Timetable Management</h2>
        <p>Add, view, edit or delete timetable slots</p>
    </div>

    <?php if (isset($_SESSION['message'])): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <?= $_SESSION['message']; ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

        <?php unset($_SESSION['message']); ?>

    <?php endif; ?>

    <!-- Add Form -->
    <div class="card-modern">

        <h5>Add Timetable Slot</h5>

        <form method="POST">

            <input type="hidden" id="id" name="id">

            <div class="row mb-3">

                <!-- Subject -->
                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control letters-only"
                        id="course"
                        name="course"
                        placeholder="Subject"
                        pattern="[A-Za-z\s]+"
                        title="Only letters are allowed"
                        required
                    >

                </div>

                <!-- Teacher -->
                <div class="col-md-3">

                    <input
                        type="text"
                        class="form-control letters-only"
                        id="faculty"
                        name="faculty"
                        placeholder="Teacher"
                        pattern="[A-Za-z\s]+"
                        title="Only letters are allowed"
                        required
                    >

                </div>

                <!-- Time -->
                <div class="col-md-3">

                    <input
                        type="time"
                        class="form-control"
                        id="timeslot"
                        name="timeslot"
                        required
                    >

                </div>

                <!-- Room -->
                <div class="col-md-2">

                    <input
                        type="text"
                        class="form-control"
                        id="room_no"
                        name="room_no"
                        placeholder="Room"
                        min="1"
                        required
                    >

                </div>

            </div>

            <button type="submit" class="btn btn-primary">
                Save Slot
            </button>

        </form>
    </div>

    <!-- Table -->
    <div class="card-modern mt-5">

        <h5>View Timetable</h5>

        <div class="table-responsive">

            <table class="table table-hover">

                <thead>

                    <tr>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($timetable_records as $record): ?>

                        <tr>

                            <td><?= $record['course'] ?></td>
                            <td><?= $record['faculty'] ?></td>
                            <td><?= $record['timeslot'] ?></td>
                            <td><?= $record['room_no'] ?></td>

                            <td>

                                <button
                                    class="btn btn-sm btn-primary edit-btn"

                                    data-id="<?= $record['_id'] ?>"
                                    data-course="<?= $record['course'] ?>"
                                    data-faculty="<?= $record['faculty'] ?>"
                                    data-timeslot="<?= $record['timeslot'] ?>"
                                    data-room_no="<?= $record['room_no'] ?>"
                                >
                                    Edit
                                </button>

                                <button
                                    class="btn btn-sm btn-danger delete-btn"
                                    data-id="<?= $record['_id'] ?>"
                                >
                                    Delete
                                </button>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <form method="POST" class="modal-content">

            <div class="modal-header">

                <h5>Edit Timetable</h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <div class="modal-body">

                <input type="hidden" name="id" id="edit_id">

                <div class="row">

                    <!-- Subject -->
                    <div class="col-md-6">

                        <input
                            type="text"
                            class="form-control letters-only"
                            name="course"
                            id="edit_course"
                            pattern="[A-Za-z\s]+"
                            title="Only letters are allowed"
                            required
                        >

                    </div>

                    <!-- Teacher -->
                    <div class="col-md-6">

                        <input
                            type="text"
                            class="form-control letters-only"
                            name="faculty"
                            id="edit_faculty"
                            pattern="[A-Za-z\s]+"
                            title="Only letters are allowed"
                            required
                        >

                    </div>

                    <!-- Time -->
                    <div class="col-md-6 mt-2">

                        <input
                            type="time"
                            class="form-control"
                            name="timeslot"
                            id="edit_timeslot"
                            required
                        >

                    </div>

                    <!-- Room -->
                    <div class="col-md-6 mt-2">

                        <input
                            type="number"
                            class="form-control"
                            name="room_no"
                            id="edit_room_no"
                            min="1"
                            required
                        >

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>

                <button type="submit" class="btn btn-primary">
                    Update
                </button>

            </div>

        </form>

    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-danger text-white">

                <h5>Delete Confirmation</h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <div class="modal-body">
                Are you sure you want to delete this record?
            </div>

            <div class="modal-footer">

                <a href="#" id="deleteConfirmBtn" class="btn btn-danger">
                    Delete
                </a>

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>

            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

var editModal = new bootstrap.Modal(
    document.getElementById('editModal')
);

var deleteModal = new bootstrap.Modal(
    document.getElementById('deleteModal')
);

// Edit Button
document.querySelectorAll('.edit-btn').forEach(function(btn){

    btn.addEventListener('click', function(){

        document.getElementById('edit_id').value =
            btn.dataset.id;

        document.getElementById('edit_course').value =
            btn.dataset.course;

        document.getElementById('edit_faculty').value =
            btn.dataset.faculty;

        document.getElementById('edit_timeslot').value =
            btn.dataset.timeslot;

        document.getElementById('edit_room_no').value =
            btn.dataset.room_no;

        editModal.show();
    });
});

// Delete Button
document.querySelectorAll('.delete-btn').forEach(function(btn){

    btn.addEventListener('click', function(){

        document.getElementById('deleteConfirmBtn').href =
            "?delete_id=" + btn.dataset.id;

        deleteModal.show();
    });
});

// Prevent Numbers in Subject & Teacher Fields
document.querySelectorAll('.letters-only').forEach(function(input){

    input.addEventListener('input', function(){

        this.value = this.value.replace(/[^A-Za-z\s]/g, '');

    });

});

</script>

<?php include_once "../templates/footer.php"; ?>