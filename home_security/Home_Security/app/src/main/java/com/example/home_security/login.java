package com.example.home_security;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.view.accessibility.AccessibilityNodeInfo;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.NetworkResponse;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.BreakIterator;
import java.util.HashMap;
import java.util.Map;

public class login extends AppCompatActivity implements View.OnClickListener {

    EditText username;
    EditText password;
    Button btn;
    TextView txt;


    @SuppressLint("WrongViewCast")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        username = findViewById(R.id.editTextTextPersonName2);
        password = findViewById(R.id.editTextTextPersonName3);
        btn = findViewById(R.id.button);

        txt = findViewById(R.id.textView3);

        // for goto registration page
        txt.setOnClickListener(this);
        btn.setOnClickListener(this);
    }
    @Override
    public void onClick(View view) {
        if (txt == view){
            Intent in = new Intent(login.this,signup.class);
            startActivity(in);
        }
        else{
            String username1 = username.getText().toString();
            String password1 = password.getText().toString();



            if (username1.length()<1){
                username.setError(" ");
            }
            else if(password1.length()<1){
                password.setError(" ");
            }
            else{

                // session
                SharedPreferences  sh= PreferenceManager.getDefaultSharedPreferences(getApplicationContext());
                String url= sh.getString("url","")+"login1  ";
                Toast.makeText(getApplicationContext(), url, Toast.LENGTH_SHORT).show();
                VolleyMultipartRequest volleyMultipartRequest = new VolleyMultipartRequest(Request.Method.POST, url,
                        new Response.Listener<NetworkResponse>(){
                            @Override
                            public void onResponse(NetworkResponse response) {
                                try {



                                    JSONObject obj = new JSONObject(new String(response.data));

                                    if(obj.getString("status").equals("ok")){
                                        Toast.makeText(getApplicationContext(), "Login success", Toast.LENGTH_SHORT).show();
                                        SharedPreferences sh = PreferenceManager.getDefaultSharedPreferences(getApplicationContext());
                                        SharedPreferences.Editor ed = sh.edit();
                                        ed.putString("lid",obj.getString("lid"));
                                        ed.putString("uid",obj.getString("uid"));
                                        ed.commit();
                                        Intent i = new Intent(getApplicationContext(), home.class);
                                        startActivity(i);
                                    }
                                    else{
                                        Toast.makeText(getApplicationContext(),"Invalid User" ,Toast.LENGTH_SHORT).show();
                                    }

                                } catch (JSONException e) {
                                    e.printStackTrace();
                                    Toast.makeText(getApplicationContext(),"User not exist " +e.getMessage().toString(),Toast.LENGTH_SHORT).show();
                                }
                            }
                        },
                        new Response.ErrorListener() {
                            @Override
                            public void onErrorResponse(VolleyError error) {
                                Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
                            }
                        }) {


                    @Override
                    protected Map<String, String> getParams() throws AuthFailureError {
                        Map<String, String> params = new HashMap<>();
                        SharedPreferences o = PreferenceManager.getDefaultSharedPreferences(getApplicationContext());
                        params.put("username", username1);//passing to python
                        params.put("password", password1);//passing to python

                        return params;
                    }


                    @Override
                    protected Map<String, DataPart> getByteData() {
                        Map<String, DataPart> params = new HashMap<>();
                        long imagename = System.currentTimeMillis();
                        //    params.put("pic", new DataPart(imagename + ".png", getFileDataFromDrawable(bitmap)));
                        return params;
                    }
                };

                Volley.newRequestQueue(getApplicationContext()).add(volleyMultipartRequest);
            }
        }
    }


    public void onPointerCaptureChanged(boolean hasCapture) {
        super.onPointerCaptureChanged(hasCapture);
    }
}
