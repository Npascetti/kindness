import {Injectable} from "@angular/core";

import {Status} from "../classes/status";
import {Hub} from "../classes/hub";
import {Point} from "../classes/point";
import {Observable} from "rxjs/Observable";
import {HttpClient, HttpParams} from "@angular/common/http";

@Injectable ()
export class HubService {

	constructor(protected http : HttpClient ) {}

	//define the API endpoint
	private hubUrl = "api/hub/";

	// call to the hub API and delete the hub in question
	deleteHub(hubId: string) : Observable<Status> {
		return(this.http.delete<Status>(this.hubUrl + hubId));

	}

	// call to the hub API and edit the hub in question
	editHub(hub : Hub) : Observable<Status> {
		return(this.http.put<Status>(this.hubUrl + hub.hubId, hub));
	}

	// call to the hub API and create the hub in question
	createHub(hub : Hub) : Observable<Status> {
		return(this.http.post<Status>(this.hubUrl, hub));
	}

	// call to the hub API and get a hub object based on its Id
	getHub(id : string) : Observable<Hub[]> {
		return(this.http.get<Hub[]>(this.hubUrl + id));
	}

	// call to the API and get an array of hubs based off the userId
	getHubByUserId(hubUserId : string) : Observable<Hub[]> {
		return(this.http.get<Hub[]>(this.hubUrl + hubUserId));

	}

	// call to hub API and get an array of hubs based off the hubContent
	getHubByHubName(hubName : string) : Observable<Hub[]> {
		return(this.http.get<Hub[]>(this.hubUrl + hubName));

	}

	//call to the API and get an array of all the hubs in the database
	getAllHubs() : Observable<Hub[]> {
		return(this.http.get<Hub[]>(this.hubUrl));

	}

    //call to the API and get an array of all the hubs in the database
    getHubPoints() : Observable<Point[]> {
        return(this.http.get<Point[]>(this.hubUrl, {params: new HttpParams().set("hubPoint", "yes")}));

    }





}