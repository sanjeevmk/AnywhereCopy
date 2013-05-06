package com.anywherecopy;

import java.io.BufferedReader;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
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
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Typeface;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.util.Log;
import android.view.Menu;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.TextView;

public class AnywhereCopy extends Activity {
	
	public Boolean signedIn = false;
	public StringBuffer bufferReceived = new StringBuffer("");
	public String username = new String("");
	public static String PREF_NAME = new String();
	public static String PREF_ID = new String();
	public ProgressBar pBar;
	public Handler handler = new Handler();
	
	public int progressStatus = 0;
	String userId = new String();
	String TAG = new String("Anywhere Copy");
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		Log.e(TAG, "Activity Created");
		
		SharedPreferences pref = getSharedPreferences(AnywhereCopy.PREF_NAME,MODE_PRIVATE);
		String usernamesaved = pref.getString(AnywhereCopy.PREF_ID, null);
		
		try {
			FileInputStream fis = openFileInput("acpin");
		} catch (FileNotFoundException e1) {
			// TODO Auto-generated catch block
			usernamesaved="";
		}
		
		if(usernamesaved==null || usernamesaved==""){
		
			setContentView(R.layout.activity_anywhere_copy);
			
			Typeface tf = Typeface.createFromAsset(getAssets(), "fonts/ahronbd.ttf");
			TextView signIn = (TextView)findViewById(R.id.signIn); 
			final EditText uName = (EditText)findViewById(R.id.enterUser); 
			final TextView uNameError = (TextView)findViewById(R.id.enterUserName);
			final Button signInButton = (Button)findViewById(R.id.signInButton);
			
			signInButton.setOnClickListener(new Button.OnClickListener(){
	
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					
					if(uName.getText().equals("")){
						uNameError.setText("Enter User name");
						return;
					}
				
					final ProgressDialog progressDialog = ProgressDialog.show(AnywhereCopy.this, "", "Signing In");
					
					final Handler handler = new Handler(){
						public void handleMessage(Message msg){
							progressDialog.dismiss();
							setContentView(R.layout.startusing);
						}
					};
						new Thread(new Runnable(){
							public void run(){
								try{
		
									username = uName.getText().toString();
									HttpClient httpClient = new DefaultHttpClient();
									HttpPost httppost = new HttpPost("http://anywherecopy.elasticbeanstalk.com/signin.php");
											
									List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
									nameValuePairs.add(new BasicNameValuePair("uname",username));
								
									httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
									HttpResponse response = httpClient.execute(httppost);
								
									BufferedReader in = new BufferedReader(new InputStreamReader(response.getEntity().getContent()));
									StringBuffer sb = new StringBuffer("");
									String line = "";
										
									while((line = in.readLine())!=null){
										sb.append(line);
									}
									
									in.close();
								
									Log.e(TAG,sb.toString());
									bufferReceived = sb;
									
									if(bufferReceived.toString().equalsIgnoreCase("success")){
										userId = username;
										Log.e(TAG,"Success received");
										try{
											FileOutputStream fos = openFileOutput("acpin",Context.MODE_PRIVATE);
											fos.write(userId.getBytes());
											fos.close();
										}catch(Exception e){
											
										}
										Log.e(TAG,"Success received again");
										getSharedPreferences(PREF_NAME,MODE_PRIVATE)
											.edit()
											.putString(PREF_ID,userId)
											.commit();
										
										signedIn =  true;
										
										
									}
								}catch(Exception e){
									
								}
								handler.sendEmptyMessage(0);
							}
						 }
						).start();
				}
			});
			
			signIn.setTypeface(tf);
			uName.setTypeface(tf);
			signInButton.setTypeface(tf);
			}else{
					startActivity(new Intent(this,PasteFromServer.class));
				}
					
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
		Log.e(TAG, "Activity Started");
	}
	
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_anywhere_copy, menu);
		return true;
	}
}
