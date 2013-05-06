package com.anywherecopy;

import java.io.BufferedReader;
import java.io.FileInputStream;
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
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.util.Log;

public class PasteFromServer extends Activity{

	String TAG = "PASTE";
	String tmpReceived,received;
	
	protected void onCreate(Bundle savedInstanceState){
		super.onCreate(savedInstanceState);
		setContentView(R.layout.backgroundlayout);
		Log.e(TAG, "paste called");
		
		SharedPreferences pref = getSharedPreferences(AnywhereCopy.PREF_NAME,MODE_PRIVATE);
		String userId = pref.getString(AnywhereCopy.PREF_ID, null);
		
		if(userId==null){
			try {
				FileInputStream fis = openFileInput("acpin");
				byte[] pinBytes = new byte[16];
				fis.read(pinBytes);
				userId = new String(pinBytes);
			} catch (Exception e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
		
		Log.e(TAG,userId);
		
		final ProgressDialog progressDialog = ProgressDialog.show(PasteFromServer.this, "", "Pasting...");
		
		final Handler handler = new Handler(){
			public void handleMessage(Message msg){
				progressDialog.dismiss();
				Intent shareIntent = new Intent(Intent.ACTION_SEND);
				Log.e(TAG,received);
				shareIntent.putExtra(Intent.EXTRA_TEXT, received);
				shareIntent.setType("text/plain");
				startActivity(Intent.createChooser(shareIntent, "Paste To"));
			}
		};
		
		new Thread(new Runnable(){
				public void run(){
				
					SharedPreferences pref = getSharedPreferences(AnywhereCopy.PREF_NAME,MODE_PRIVATE);
					String userId = pref.getString(AnywhereCopy.PREF_ID, null);
					
					if(userId==null){
						try {
							FileInputStream fis = openFileInput("acpin");
							byte[] pinBytes = new byte[16];
							fis.read(pinBytes);
							userId = new String(pinBytes);
						} catch (Exception e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
					}
					
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
						handler.sendEmptyMessage(0);
					}catch (Exception e){
									
						}
				}
		}).start();
		
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
