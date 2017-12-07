import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";

import {Status} from "../classes/status";
import {User} from "../classes/user";
import {Observable} from "rxjs/Observable";

@Injectable ()
export class UserService  {


	constructor(protected http : HttpClient) {

	}

	//define the API endpoint
	private userUrl = "api/user/";

	//reach out to the user  API and delete the user in question
	deleteUser(id : string) : Observable<Status> {
		return(this.http.delete<Status>(this.userUrl + id));
	}

	// call to the User API and edit the user in question
	editUser(user: User) : Observable<Status> {
		return(this.http.put<Status>(this.userUrl , user));
	}

	// call to the User API and get a User object by its id
	getUser(id: string) : Observable<User> {
		return(this.http.get<User>(this.userUrl + id));

	}

	// call to the API to grab an array of users based on the user input
	getUserByUserEmail(userEmail: string) :Observable<User> {
		return(this.http.get<User>(this.userUrl + userEmail));

	}

	//call to the user API and grab the corresponding user by its email
	getUserByUserUserName(userUserName: string) :Observable<User> {
		return(this.http.get<User>(this.userUrl + "?userUserName=" + userUserName));
	}

	getUserByUserActivationToken(userActivationToken: string) :Observable<User> {
		return(this.http.get<User>(this.userUrl + "?userActivationToken=" + userActivationToken));
	}
}