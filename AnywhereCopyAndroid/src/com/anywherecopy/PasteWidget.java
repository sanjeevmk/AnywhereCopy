package com.anywherecopy;

import android.app.PendingIntent;
import android.appwidget.AppWidgetManager;
import android.appwidget.AppWidgetProvider;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.widget.RemoteViews;

public class PasteWidget extends AppWidgetProvider{
	public void onUpdate(Context ctx, AppWidgetManager appWidgetManager, int[] appWidgetIds){
		super.onUpdate(ctx, appWidgetManager, appWidgetIds);
		
		Intent widgetClicked = new Intent(ctx,PasteFromServer.class);
		widgetClicked.setAction(Intent.ACTION_SEND);
		PendingIntent widgetClickedPending = PendingIntent.getActivity(ctx, 0, widgetClicked,0);
		RemoteViews remoteViews = new RemoteViews(ctx.getPackageName(),R.layout.paste_widget_layout);
		remoteViews.setOnClickPendingIntent(R.id.WidgetArea, widgetClickedPending);
		
		ComponentName thisWidget = new ComponentName(ctx,PasteWidget.class);
		AppWidgetManager manager = AppWidgetManager.getInstance(ctx);
		manager.updateAppWidget(thisWidget, remoteViews);
	}
}
