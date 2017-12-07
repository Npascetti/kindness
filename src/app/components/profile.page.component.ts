import {Component, OnInit} from "@angular/core";
import {JwtHelperService} from "@auth0/angular-jwt";
import {User} from "../classes/user";

//enable Jquery $ alias
declare const $: any;

@Component({
	templateUrl: "./templates/profile-page.html"
})

export class ProfilePageComponent implements OnInit{

	user:User = new User(null, null, null, null, null, null, null, null, null) ;

	authObj: any = {};

	constructor (
		private jwtHelperService: JwtHelperService,
	){}

	ngOnInit() : void {}

	getJwtProfileId() : any {
			this.authObj = this.jwtHelperService.decodeToken(localStorage.getItem("jwt-token"));
	}
}

