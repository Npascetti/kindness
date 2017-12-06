<div class="modal fade" id="editHubModal" tabindex="-1" role="dialog" aria-labelledby="editHubModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editHubModalLabel">Edit Hub</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				Edit your hub.
			</div>
			<div class="modal-header">
				<form [formGroup]="signUpForm" (ngSubmit)="createSignUp();" novalidate>

					<!-- hub name -->
					<div class="form-group">
						<input type="text" id="hubName" name="hubName" formControlName="hubName" class="form-control" placeholder="New Hub Name">
					</div>

					<!--					<div class="alert alert-danger"-->
					<!--						  *ngIf="signUpForm.controls.userName?.invalid && signUpForm.controls.userName?.touched">-->
					<!--						<p *ngIf="signUpForm.controls.userName?.errors.maxlength">User Name is too long</p>-->
					<!--						<p *ngIf="signUpForm.controls.userName?.errors.required">User Name required</p>-->
					<!--					</div>-->

					<!--location-->
					<div class="form-group">
						<input type="text" id="location" name="location" class="form-control"
								 formControlName="location" placeholder=" New Location">
					</div>

					<!--					<div class="alert alert-danger"-->
					<!--						  *ngIf="signUpForm.controls.firstName?.invalid && signUpForm.controls.firstName?.touched">-->
					<!--						<p *ngIf="signUpForm.controls.firstName?.errors.maxlength">Name is too long</p>-->
					<!--						<p *ngIf="signUpForm.controls.firstName?.errors.required">Name required</p>-->
					<!--					</div>-->

					<!-- Submit button -->
					<button type="submit" id="submit" name="signUp" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
