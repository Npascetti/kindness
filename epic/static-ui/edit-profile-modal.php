<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				Edit your KindHub profile.
			</div>
			<div class="modal-header">
				<form [formGroup]="signUpForm" (ngSubmit)="createSignUp();" novalidate>

					<!-- user name -->
					<div class="form-group">
						<input type="text" id="userName" name="userName" formControlName="userName" class="form-control" placeholder="Username">
					</div>

<!--					<div class="alert alert-danger"-->
<!--						  *ngIf="signUpForm.controls.userName?.invalid && signUpForm.controls.userName?.touched">-->
<!--						<p *ngIf="signUpForm.controls.userName?.errors.maxlength">User Name is too long</p>-->
<!--						<p *ngIf="signUpForm.controls.userName?.errors.required">User Name required</p>-->
<!--					</div>-->

					<!-- users  first name-->
					<div class="form-group">
						<input type="text" id="firstName" name="firstName" class="form-control"
								 formControlName="firstName" placeholder="First Name">
					</div>

<!--					<div class="alert alert-danger"-->
<!--						  *ngIf="signUpForm.controls.firstName?.invalid && signUpForm.controls.firstName?.touched">-->
<!--						<p *ngIf="signUpForm.controls.firstName?.errors.maxlength">Name is too long</p>-->
<!--						<p *ngIf="signUpForm.controls.firstName?.errors.required">Name required</p>-->
<!--					</div>-->


					<!-- users last name -->
					<div class="form-group">
						<input type="text" id="lastName" name="lastName" class="form-control"
								 formControlName="lastName" placeholder="Last Name">
					</div>

<!--					<div class="alert alert-danger"-->
<!--						  *ngIf="signUpForm.controls.lastName?.invalid && signUpForm.controls.lastName?.touched">-->
<!--						<p *ngIf="signUpForm.controls.lastName?.errors.maxlength">Name is too long</p>-->
<!--						<p *ngIf="signUpForm.controls.lastName?.errors.required">Name required</p>-->
<!--					</div>-->

					<!-- users  email-->
					<div class="form-group">
						<input type="email" id="email" name="email" class="form-control" formControlName="email" placeholder="Email">
					</div>

<!--					<div class="alert alert-danger"-->
<!--						  *ngIf="signUpForm.controls.email?.invalid && signUpForm.controls.email?.touched">-->
<!--						<p *ngIf="signUpForm.controls.email?.errors.maxlength">email is to long</p>-->
<!--						<p *ngIf="signUpForm.controls.email?.errors.required">valid email address is required</p>-->
<!--					</div>-->

					<!-- user password -->
					<div class="form-group">
						<input type="password" id="password" name="password" class="form-control" formControlName="password" placeholder="New Password">
					</div>
<!--					<div class="alert alert-danger"-->
<!--						  *ngIf="signUpForm.controls.password?.invalid && signUpForm.controls.password?.touched">-->
<!--						<p *ngIf="signUpForm.controls.password?.errors.maxlength">Password too long</p>-->
<!--						<p *ngIf="signUpForm.controls.password?.errors.required">Valid password required</p>-->
<!--					</div>-->


					<!--confirm password -->
					<div class="form-group">
						<input type="password" id="passwordConfirm" name="passwordConfirm" class="form-control" formControlName="passwordConfirm" placeholder="Confirm Password">
					</div>

<!--					<div class="alert alert-danger"-->
<!--						  *ngIf="signUpForm.controls.passwordConfirm?.invalid && signUpForm.controls.passwordConfirm?.touched">-->
<!--						<p *ngIf="signUpForm.controls.passwordConfirm?.errors.maxlength">Password is too long</p>-->
<!--						<p *ngIf="signUpForm.controls.passwordConfirm?.errors.required">Must confirm password</p>-->
<!--					</div>-->

					<!-- Submit button -->
					<button type="submit" id="submit" name="signUp" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>

