<div class="modal fade" id="deleteProfileModal" tabindex="-1" role="dialog" aria-labelledby="deleteProfileModal"
	  aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteProfileModal">Delete KindHub Profile</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body text-danger">
				Deleting your profile will delete all of your associated hubs!
			</div>
			<div class="modal-body">
				Thank you for all your hard work! We hope to see you again!
			</div>
			<div class="modal-header">
				<form [formGroup]="signUpForm" (ngSubmit)="createSignUp();" novalidate>

					<!-- delete + cancel button -->
					<button type="submit" id="deleteProfile" name="deleteProfile" class="btn btn-danger">Delete Profile</button>
					<button type="button" id="cancel" name="cancel" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Cancel</button>
				</form>
			</div>
<!--			<div *ngIf="status !== null" class="alert alert-dismissible" [ngClass]="status.type" role="alert">-->
<!--				<button type="button" class="close" aria-label="Close" (click)="status = null;"><span-->
<!--						aria-hidden="true">&times;</span></button>-->
<!--				{{ status.message }}-->
<!--			</div>-->
		</div>
	</div>
</div>