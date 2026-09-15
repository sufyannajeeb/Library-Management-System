package com.example.home_security;

import android.app.Activity;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.squareup.picasso.Picasso;

public class Cust_visit extends ArrayAdapter<String>  {

    private Activity context;       //for to get current activity context
    SharedPreferences sh;
    private String[] file,fname,lname,email,photo,place,dist;
    String rate;


    public Cust_visit(Activity context,String[] fname, String[] lname, String[] email, String[] photo) {
        //constructor of this class to get the values from main_activity_class

        super(context, R.layout.activity_cust_visit, fname);
        this.context = context;
        this.photo = photo;
        this.fname=fname;
        this.lname=lname;
        this.email=email;


//


    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        //override getView() method

        LayoutInflater inflater = context.getLayoutInflater();
        View listViewItem = inflater.inflate(R.layout.activity_cust_visit, null, true);

        TextView t=(TextView)listViewItem.findViewById(R.id.fname);
        TextView t1=(TextView)listViewItem.findViewById(R.id.lname);
        TextView t2=(TextView)listViewItem.findViewById(R.id.email);
        ImageView im=(ImageView) listViewItem.findViewById(R.id.imageView);



        t.setText(fname[position]);
        t1.setText( lname[position]);
        t2.setText( email[position]);






        sh=PreferenceManager.getDefaultSharedPreferences(getContext());

        String pth = sh.getString("url", "")+photo[position];
        pth = pth.replace("~", "");
//	       Toast.makeText(context, pth, Toast.LENGTH_LONG).show();

        Log.d("-------------", pth);
        Picasso.with(context)
                .load(pth)
                .placeholder(R.drawable.ic_launcher_background)
                .error(R.drawable.ic_launcher_background).into(im);

        return  listViewItem;
    }

    private TextView setText(String string) {
        // TODO Auto-generated method stub
        return null;
    }
}