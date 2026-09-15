package com.example.home_security;

import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.TextView;

public class custom_view_complaint_reply extends BaseAdapter {
    String[] id, complaint, reply,date;
    private Context context;
    Button b1;

    public custom_view_complaint_reply(Context applicationContext,String[] id, String[] complaint, String[] reply, String[] date) {
        this.context = applicationContext;
        this.id=id;
        this.complaint = complaint;
        this.reply = reply;
        this.date = date;

    }

    @Override
    public int getCount() {
        return reply.length;
    }

    @Override
    public Object getItem(int i) {
        return null;
    }

    @Override
    public long getItemId(int i) {
        return 0;
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        LayoutInflater inflator = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);

        View gridView;
        if (view == null) {
            gridView = new View(context);
            //gridView=inflator.inflate(R.layout.customview, null);
            gridView = inflator.inflate(R.layout.custom_view_complaint_reply, null);//same class name

        } else {
            gridView = (View) view;

        }
//        ImageView tv7=(ImageView)gridView.findViewById(R.id.imageView2);
        TextView tv1 = (TextView) gridView.findViewById(R.id.textView);
        TextView tv2 = (TextView) gridView.findViewById(R.id.textView3);
        TextView tv3 = (TextView) gridView.findViewById(R.id.textView4);

//        TextView tv3=(TextView)gridView.findViewById(R.id.textView11);
//        TextView tv4=(TextView)gridView.findViewById(R.id.textView8);
//        Button tv6=(Button)gridView.findViewById(R.id.button6);
//        Button tv7=(Button)gridView.findViewById(R.id.button3);

//
//        Button tv6=(Button)gridView.findViewById(R.id.button3);


        tv1.setTextColor(Color.BLACK);//color setting
        tv2.setTextColor(Color.BLACK);
        tv3.setTextColor(Color.BLACK);

//        tv3.setTextColor(Color.BLACK);
//        tv4.setTextColor(Color.BLACK);
//        tv5.setTextColor(Color.BLACK);


        tv1.setText("Complaint :"+complaint[i]);
        tv2.setText("Reply :"+reply[i]);
        tv3.setText("Date :"+date[i]);

//        tv3.setText(eid[i]);
//        tv4.setText(p[i]);
//        tv5.setText(ph[i]);

//        tv6.setTag(sid[i]);
//        tv7.setTag(sid[i]);

        // Load image using Picasso

//
        // Load image using Picasso

//        String imgUrl = sh.getString("url", "") + photo[i]; //viewing image
//        Picasso.with(context).load(imgUrl).into(tv7);


//
        // Set OnClickListener for the button
//        tv6.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View v) {
//                // Retrieve the tid associated with the clicked button
//                String clickedTid = (String) v.getTag();
//
//                // Call your function with the tid
//                handleButton1Click(clickedTid);
//            }
//        });
//
//
//        tv7.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View v) {
//                // Retrieve the tid associated with the clicked button
//                String clickedTid = (String) v.getTag();
//
//                // Call your function with the tid
//                handleButton2Click(clickedTid);
//            }
//        });


        return gridView;

    }
}


//    private void handleButton1Click(String sid) {
//        SharedPreferences sh = PreferenceManager.getDefaultSharedPreferences(context.getApplicationContext());
//        SharedPreferences.Editor ed = sh.edit();//session created for company_id .here cid is company_id .it is passed to view_vacany.java.
//        ed.putString("sid",sid);
//        ed.commit();
//        Intent u=new Intent(context,view_notes.class);
//        u.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
//        context.startActivity(u);
//    }
//
//    private void handleButton2Click(String sid) {
//        SharedPreferences sh = PreferenceManager.getDefaultSharedPreferences(context.getApplicationContext());
//        SharedPreferences.Editor ed = sh.edit();//session created for company_id .here cid is company_id .it is passed to view_vacany.java.
//        ed.putString("sid",sid);
//        ed.commit();
//        Intent u=new Intent(context,ask_doubts.class);
//        u.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
//        context.startActivity(u);
//    }





