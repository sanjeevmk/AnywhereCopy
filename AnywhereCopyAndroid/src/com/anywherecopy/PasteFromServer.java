package com.anywherecopy;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;

import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.ImageView;

public class PasteFromServer extends Activity{

	String TAG = "PASTE";
	String tmpReceived,received;
	
	protected void onCreate(Bundle savedInstanceState){
		super.onCreate(savedInstanceState);
		setContentView(R.layout.backgroundlayout);
		ImageView pasteProgress = (ImageView)findViewById(R.id.pasteprogress);
		Log.e(TAG, "paste called");
		
		pasteProgress.setVisibility(View.VISIBLE);
		SharedPreferences pref = getSharedPreferences(AnywhereCopy.PREF_NAME,MODE_PRIVATE);
		String userId = pref.getString(AnywhereCopy.PREF_ID, null);
		Log.e(TAG,userId);
			
		HttpClient httpClient = new DefaultHttpClient();
		HttpPost httppost = new HttpPost("http://anywherecopy.elasticbeanstalk.com/anywherepaste.php");
					
		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(1);
		nameValuePairs.add(new BasicNameValuePair("userId",userId));
					
		try{
			httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
			HttpResponse response = httpClient.execute(httppost);
					
			BufferedReader in = new BufferedReader(new InputStreamReader(response.getEntity().getContent()));
			StringBuffer sb = new StringBuffer("");
					
			while((received = in.readLine())!=null){
				sb.append(received);
			}
			received = sb.toString();
			in.close();
		}catch (Exception e){
						
			}
					
		Intent shareIntent = new Intent(Intent.ACTION_SEND);
		Log.e(TAG,received);
		shareIntent.putExtra(Intent.EXTRA_TEXT, received);
		shareIntent.setType("text/plain");
		startActivity(Intent.createChooser(shareIntent, "Paste To"));
	}
	
	protected void onPause(){
		super.onPause();
		finish();
	}
	
	public void onBackPressed(){
		super.onBackPressed();
		finish();
	}
	
	protected void onStart(){
		super.onStart();
		Log.e(TAG, "Paste Started");
	}
}
