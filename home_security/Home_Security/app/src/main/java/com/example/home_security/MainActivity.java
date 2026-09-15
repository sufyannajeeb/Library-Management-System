package com.example.home_security;

import android.content.Intent;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

public class MainActivity extends AppCompatActivity implements View.OnClickListener {

    EditText e_ip ;
    Button bt ;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        e_ip=findViewById(R.id.editTextTextPersonName);
        bt = findViewById(R.id.button2);
        bt.setOnClickListener(this);
        SharedPreferences sh = PreferenceManager.getDefaultSharedPreferences(getApplicationContext());
        e_ip.setText(sh.getString("ip",""));
    }

    @Override
    // for goto login page
    public void onClick(View view) {
        String ip = e_ip.getText().toString();
        if(ip.length()<1){
            e_ip.setError("");
        }else{
            SharedPreferences sh = PreferenceManager.getDefaultSharedPreferences(getApplicationContext());
            SharedPreferences.Editor ed = sh.edit();
            ed.putString("ip",ip);
            ed.putString("url","http://"+ip+":5004/");
            ed.commit();
            Intent i = new Intent(getApplicationContext(),login.class);
            startActivity(i);
        }
    }
}
