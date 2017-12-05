<div class="modal fade" id="createHubModal" tabindex="-1" role="dialog" aria-labelledby="createHubModalLabel"
	  aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="createHubModal">Create a Hub</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				Create a KindHub to easily help your neighbors.
			</div>
			<div class="modal-header">
				<form [formGroup]="signUpForm" (ngSubmit)="createSignUp();" novalidate>

					<!-- hub name -->
					<div class="form-group">
						<label for="hubName">Hub Name</label>
						<input type="text" id="hubName" name="hubName" formControlName="hubName" class="form-control" placeholder="Downtown Hub">
					</div>

<!--					<div class="alert alert-danger"-->
<!--						  *ngIf="signUpForm.controls.userName?.invalid && signUpForm.controls.userName?.touched">-->
<!--						<p *ngIf="signUpForm.controls.userName?.errors.maxlength">Hub Name is too long</p>-->
<!--						<p *ngIf="signUpForm.controls.userName?.errors.required">Hub Name required</p>-->
<!--					</div>-->

					<!-- hub location-->

					<div class="form-group">
						<label for="inputAddress">Address</label>
						<input type="text" id="inputAddress" class="form-control" formControlName="inputAddress" placeholder="1234 Main St">
					</div>

<!--					<div class="alert alert-danger"-->
<!--						  *ngIf="signUpForm.controls.firstName?.invalid && signUpForm.controls.firstName?.touched">-->
<!--						<p *ngIf="signUpForm.controls.firstName?.errors.maxlength">Name is too long</p>-->
<!--						<p *ngIf="signUpForm.controls.firstName?.errors.required">Name required</p>-->
<!--					</div>-->

					<div class="form-group">
						<label for="inputZip">Zip Code</label>
						<input type="text" id="inputZip" class="form-control" formControlName="inputAddress" placeholder="87102"/>
					</div>

					<!-- Submit button -->
					<button type="submit" id="submit" name="signUp" class="btn btn-info">Submit</button>
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