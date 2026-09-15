package com.example.home_security;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

public class home extends AppCompatActivity implements View.OnClickListener {
    Button b1, b2, b3, b4,b5,b6,b7;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);
        b1 = findViewById(R.id.button4);
        b2 = findViewById(R.id.button8);
        b3 = findViewById(R.id.button7);
        b4 = findViewById(R.id.button5);
        b5 = findViewById(R.id.button6);
        b6 = findViewById(R.id.button10);
        b7 = findViewById(R.id.button11);
        b1.setOnClickListener(this);
        b2.setOnClickListener(this);
        b3.setOnClickListener(this);
        b4.setOnClickListener(this);
        b5.setOnClickListener(this);
        b6.setOnClickListener(this);
        b7.setOnClickListener(this);
    }

    @Override
    public void onClick(View view) {
        if (view == b1) {
            Intent i = new Intent(getApplicationContext(), manage_family.class);
            startActivity(i);
        } else if (view == b2) {
            Intent i = new Intent(getApplicationContext(), send_complaint.class);
            startActivity(i);
        }else if (view == b3) {
            Intent i = new Intent(getApplicationContext(), view_complaint_reply.class);
            startActivity(i);
        } else if (view == b4) {
            Intent i = new Intent(getApplicationContext(), report_to_police_station.class);
            startActivity(i);
        } else if (view == b5) {
            Intent i = new Intent(getApplicationContext(), view_reply.class);
            startActivity(i);
        }else if (view == b6) {
            Intent i = new Intent(getApplicationContext(), View_visitorlog.class);
            startActivity(i);
        }else if (view == b7) {
            Intent i = new Intent(getApplicationContext(), View_alert.class);
            startActivity(i);
        }
    }
}