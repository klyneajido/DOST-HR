<!-- Job Deletion Modal -->
<div class="modal fade" id="passwordModalJob" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">Confirm Deletion</h5>
            </div>
            <div class="modal-body">
                <form id="deleteForm">
                    <input type="hidden" id="deleteJobId" name="id" value="">
                    <div class="form-group">
                        <label for="adminPassword">Admin Password</label>
                        <input type="password" class="form-control" id="adminPassword" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-danger">Delete Job</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Announcement Deletion Modal -->
<div class="modal fade" id="deleteAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="deleteAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAnnouncementModalLabel">Confirm Deletion</h5>
            </div>
            <div class="modal-body">
                <form id="deleteAnnouncementForm" action="PHP_Connections/delete_announcement.php" method="POST">
                    <input type="hidden" id="deleteAnnouncementId" name="id" value="">
                    <div class="form-group">
                        <label for="adminPasswordAnnouncement">Admin Password</label>
                        <input type="password" class="form-control" id="adminPasswordAnnouncement" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-danger">Delete Announcement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Applicant Deletion Modal -->
<div class="modal fade" id="passwordModalApplicant" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">Confirm Deletion</h5>
            </div>
            <div class="modal-body">
                <form id="deleteApplicantForm">
                    <input type="hidden" id="deleteApplicantId" name="id" value="">
                    <div class="form-group">
                        <label for="adminPasswordApplicant">Admin Password</label>
                        <input type="password" class="form-control" id="adminPasswordApplicant" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-danger">Delete Applicant</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success Deletion Modals -->
<div class="modal fade" id="successModalApplicant" tabindex="-1" role="dialog" aria-labelledby="successModalApplicantLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalApplicantLabel">Applicant Deleted</h5>
            </div>
            <div class="modal-body">
                <p>The applicant has been deleted successfully.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModalJob" tabindex="-1" role="dialog" aria-labelledby="successModalJobLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalJobLabel">Job Deleted</h5>
            </div>
            <div class="modal-body">
                <p>The job has been deleted successfully.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModalAnnouncement" tabindex="-1" role="dialog" aria-labelledby="successModalAnnouncementLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalAnnouncementLabel">Announcement Deleted</h5>
            </div>
            <div class="modal-body">
                <p>The announcement has been deleted successfully.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Restore Job Modal -->
<div class="modal fade" id="restoreJobModal" tabindex="-1" aria-labelledby="restoreJobModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreJobModalLabel">Restore Job</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to restore this job?
            </div>
            <div class="modal-footer">
                <a id="confirmRestore" href="#" class="btn btn-primary">Restore</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Restore Announcement Modal -->
<div class="modal fade" id="restoreAnnouncementModal" tabindex="-1" aria-labelledby="restoreAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreAnnouncementModalLabel">Restore Announcement</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to restore this announcement?
            </div>
            <div class="modal-footer">
                <a id="confirmAnnouncementRestore" href="#" class="btn btn-primary">Restore</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>