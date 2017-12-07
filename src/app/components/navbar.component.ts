import {Component, OnChanges, OnInit} from "@angular/core";
import {JwtHelperService} from "@auth0/angular-jwt";
import {User} from "../classes/user";

//enable Jquery $ alias
declare const $: any;

@Component({
	templateUrl: "./templates/navbar.html",
	selector: "navbar"
})

export class NavbarComponent implements OnChanges, OnInit{
	user:User = new User(null, null, null, null, null, null, null, null, null) ;

	authObj: any = {};

	constructor (
		private jwtHelperService: JwtHelperService
	){}

	ngOnInit() : void {
		this.getJwtProfileId();
	}

	ngOnChanges() : void {
			this.getJwtProfileId();
	}

	getJwtProfileId() : any {
		this.authObj = this.jwtHelperService.decodeToken(localStorage.getItem("jwt-token"));
	}
}
